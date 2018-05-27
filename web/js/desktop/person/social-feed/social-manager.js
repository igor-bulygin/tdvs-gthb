(function () {
	"use strict";

	function controller(personDataService, UtilService, languageDataService, uploadDataService,$timeout, lovedDataService, $uibModal) {
		var vm = this;
		vm.posts = [];
		vm.tempFiles=[];
		vm.showCreatePost = false;
		vm.selected_language=_lang;
		vm.stripHTMLTags = UtilService.stripHTMLTags;
		vm.truncateString = UtilService.truncateString;
		vm.viewingConnectedUser = viewingConnectedUser;
		vm.uploadPhoto = uploadPhoto;
		vm.showNewPost = showNewPost;
		vm.createPost = createPost;
		vm.lovePost = lovePost;
		vm.unLovePost = unLovePost;
		vm.maxCharacters = 18;
		vm.openPostDetailsModal = openPostDetailsModal;

		init();

		function init() {
			getLanguages();
			getPosts();
		}

		function viewingConnectedUser() {
			return UtilService.isConnectedUser(person.short_id);
		}

		function getPosts() {
			vm.loading = true;
			function onGetPostsSuccess(data) {
				vm.posts = data.items; 
				vm.loading = false;
			}

			function onGetPostsError(err) {
				vm.loading = false;
				UtilService.onError(err);
			}
			personDataService.getPost({person_id: person.short_id}, onGetPostsSuccess, onGetPostsError);

		}

		function showNewPost() {
			vm.newPost = { photo:"", text:"", person_id : person.short_id, post_state: 'post_state_active'};
			vm.showCreatePost = true;
		}


		function getLanguages() {
			function onGetLanguagesSuccess(data) {
				vm.languages = data.items;
			}
			languageDataService.getLanguages(onGetLanguagesSuccess, UtilService.onError);
		}

		function parseText(post) {
			post.completedLanguages = [];
			vm.languages.forEach(function (element) {
				if (post.text[element.code] && post.text[element.code] !== "") {
					post.completedLanguages.push(element.code)
				}
			})
		}

		function createPost() {
			vm.loading = true;
			function onGetPostsSuccess(data) {
				vm.posts.push(data); 
				vm.showCreatePost = true;
				vm.loading = false;
			}

			function onGetPostsError(err) {
				vm.loading = false;
				UtilService.onError(err);
			}
			var params = {

			}
			personDataService.publishPost(vm.newPost, onGetPostsSuccess, onGetPostsError);
		}


		function uploadPhoto(images, errImages) {
			function onUploadPhotoSuccess(data, file) {
				$timeout(function() {
					delete file.progress;
				}, 1000);
				if (!vm.newPost.photo) {
					vm.newPost.photo= {};
				}
				vm.newPost.photo= data.data.filename;
				vm.newImage=currentHost() + data.data.url;
				var index=-1;
				angular.forEach(vm.tempFiles, function(uploadingFile) {
					if (uploadingFile.$$hashKey==file.$$hashKey) {
						index=-vm.tempFiles.indexOf(uploadingFile);
					}
				});
				if (index != -1)
				{
					vm.tempFiles.splice(index,1);
				}
			}

			function onWhileUploadingPhoto(evt, file) {
				angular.forEach(vm.tempFiles, function(uploadingFile) {
					if (uploadingFile.$$hashKey==file.$$hashKey) {
						uploadingFile.progress = parseInt(100.0 * evt.loaded / evt.total);
					}
				});
			}
			angular.forEach(images, function(image) {
				vm.tempFiles.push(image);
			});
			vm.files=images
			vm.errFiles = errImages;
			//upload photos
			angular.forEach(vm.files, function(file) {
				var data = {
					type: 'post-photos',
					file: file
				};
				uploadDataService.UploadFile(data,
					function(data) {
						return onUploadPhotoSuccess(data, file);
					}, UtilService.onError,
					function(evt) {
						return onWhileUploadingPhoto(evt, file);
					});
			});
		}

		function lovePost(post) {
			if (post.person_id === UtilService.getConnectedUser() || post.isLoved) {
				return;
			}
			vm.loading = true;
			function onLovePostSuccess(data) {
				post.loveds = data.post.loveds;
				post.isLoved = data.post.isLoved;
				vm.loading = false;
			}

			function onLovePostError(err) {
				vm.loading = false;
				UtilService.onError(err);
			}
			lovedDataService.setLoved({post_id :post.id}, onLovePostSuccess, onLovePostError);
		}

		function unLovePost(post) {
			if (post.person_id === UtilService.getConnectedUser() || !post.isLoved) {
				return;
			}
			vm.loading = true;
			function onUnLovePostSuccess(data) {
				post.loveds = post.loveds -1;
				post.isLoved = false;
				vm.loading = false;
			}

			function onUnLovePostError(err) {
				vm.loading = false;
				UtilService.onError(err);
			}
			lovedDataService.deleteLovedPost({postId :post.id}, onUnLovePostSuccess, onUnLovePostError);
		}

		function openPostDetailsModal(post) {
			var modalInstance = $uibModal.open({
				component: 'modalPostDetails',
				resolve: {
					post: function() {
						return post;
					}
				}
			});
			modalInstance.result.then(function(data) {
			}, function(err) {
				UtilService.onError(err);
			})
		}


	}

	angular
		.module('person')
		.controller('socialManagerCtrl', controller);

}());