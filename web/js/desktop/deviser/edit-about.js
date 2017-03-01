(function () {
	"use strict";

	function controller(deviserDataService, UtilService, languageDataService, toastr, productDataService, 
		Upload, $timeout, $rootScope, $scope, deviserEvents, $uibModal, dragndropService, $window) {
		var vm = this;
		vm.stripHTMLTags = UtilService.stripHTMLTags;
		vm.save = save;
		vm.uploadPhoto = uploadPhoto;
		vm.openCropModal = openCropModal;
		vm.uploadCV = uploadCV;
		vm.deleteCV = deleteCV;
		vm.deleteImage = delete_image;
		vm.dragOver = dragOver;
		vm.dragStart = dragStart;
		vm.moved = moved;
		vm.canceled = canceled;
		vm.checkPhotos = checkPhotos;
		vm.biography_language = "en-US";
		vm.images = [];

		init();

		function init() {
			getDeviser();
			getLanguages();
			getCategories();
		}

		/*Initial functions*/
		function getDeviser() {
			deviserDataService.Profile.get({
				deviser_id: deviser.short_id
			}).$promise.then(function (dataDeviser) {
				vm.deviser = dataDeviser;
				vm.deviser_original = angular.copy(dataDeviser);
				vm.images = UtilService.parseImagesUrl(vm.deviser.media.photos, vm.deviser.url_images);
				vm.curriculum = currentHost() + vm.deviser.url_images + vm.deviser.curriculum;
			}, function (err) {
				UtilService.onError(err);
			});
		}

		function getCategories() {
			function onGetCategoriesSuccess(data) {
				vm.categories = data.items;
			}

			productDataService.getCategories({scope: 'roots'}, onGetCategoriesSuccess, UtilService.onError);
		}

		function getLanguages() {
			function onGetLanguagesSuccess(data) {
				vm.languages = data.items;
			}

			languageDataService.getLanguages(onGetLanguagesSuccess, UtilService.onError);
		}

		/* photos functions */
		function parsePhotos() {
			var media = vm.deviser.media;
			media.photos = [];
			vm.images.forEach(function (element) {
				if(media.photos.indexOf(element.filename) < 0)
					media.photos.push(element.filename);
			});
			return media;
		}

		function openCropModal(photo, index) {
			var modalInstance = $uibModal.open({
				component: 'modalCrop',
				resolve: {
					photo: function() {
						return photo;
					}
				}
			});

			modalInstance.result.then(function (imageCropped) {
				if(imageCropped) {
					uploadPhoto([Upload.dataUrltoBlob(imageCropped, "temp.png")], null, index, false);
				}
			}, function (err) {
				//errors
			});

		}

		function uploadPhoto(images, errImages, index, cropOption) {
			vm.files = images;
			vm.errFiles = errImages;
			angular.forEach(vm.files, function (file) {
				var data = {
					type: "deviser-media-photos",
					deviser_id: vm.deviser.id,
					file: file
				}
				Upload.upload({
					url: deviserDataService.Uploads,
					data: data
				}).then(function (dataUpload) {
					//if uplading crop, replace it
					if(index!==null && index>-1) {
						vm.deviser.media.photos[index] = dataUpload.data.filename;
					} else {
						//if not, add it and crop it
						vm.deviser.media.photos.unshift(dataUpload.data.filename);
						var imageToCrop = currentHost() + vm.deviser.url_images + dataUpload.data.filename;
						openCropModal(imageToCrop, 0);
					}
					vm.images = UtilService.parseImagesUrl(vm.deviser.media.photos, vm.deviser.url_images);
					$timeout(function () {
						delete file.progress;
					}, 1000);
				}, function (err) {
					//errors
				}, function (evt) {
					//progress
					file.progress = parseInt(100.0 * evt.loaded / evt.total);
				});
			})
		}

		function delete_image(index) {
				vm.images.splice(index, 1);
				vm.deviser.media = parsePhotos();
				checkPhotos();
		}

		function checkPhotos(){
			if(vm.images.length >= 5) {
				vm.showMaxPhotosLimit = true;
			}
			else {
				vm.showMaxPhotosLimit = false;
			}
		}

		/* cv functions */
		function uploadCV(file) {
			var data = {
				type: 'deviser-curriculum',
				deviser_id: vm.deviser.id,
				file: file
			}
			Upload.upload({
				url: deviserDataService.Uploads,
				data: data
			}).then(function (dataCV) {
				vm.deviser.curriculum = dataCV.data.filename;
			}, function (err) {
				//errors
			})
		}

		function deleteCV() {
			vm.deviser.curriculum = '';
		}

		/* drag and drop functions */

		function dragStart(index) {
			dragndropService.dragStart(index, vm.images);
		}

		function dragOver(index) {
			vm.images = dragndropService.dragOver(index, vm.images);
			return true;
		}

		function moved(index) {
			vm.images = dragndropService.moved(vm.images);
			vm.deviser.media = parsePhotos();
		}

		function canceled(){
			vm.images = dragndropService.canceled();
		}

		function save() {
			function parseTags(value) {
				return value.replace(/<[^\/>][^>]*><\/[^>]+>/gim, "");
			}

			var patch = new deviserDataService.Profile;
			patch.deviser_id = vm.deviser.id;
			for(var key in vm.deviser) {
				if(key === 'text_biography') {
					for(var language in vm.deviser[key]) {
						vm.deviser[key][language] = UtilService.stripHTMLTags(vm.deviser[key][language]);
					}
				}
				if(key !== 'account_state')
					patch[key] = angular.copy(vm.deviser[key]);
			}
			patch.$update().then(function(updateData) {
				$window.location.href = currentHost() + '/deviser/' + vm.deviser.slug + '/' + vm.deviser.id +'/about'
			}, function(err) {
				UtilService.onError(err);
			})

		}

		//events
		$scope.$on(deviserEvents.make_profile_public_errors, function(event, args) {
			//set form submitted
			vm.form.$setSubmitted();
			//set fields
			console.log(args);
			if(args.required_fields && args.required_fields.length > 0) {
				args.required_fields.forEach(function(element) {
					console.log(element);
					if(element === 'photos')
						vm.setPhotosRequired = true;
					if(element === 'biography')
						vm.setBiographyRequired = true;
					if(element === 'categories')
						vm.setCategoriesRequired = true;
				});
			}
		});

	}

	angular
		.module('todevise')
		.controller('editAboutCtrl', controller);
}());