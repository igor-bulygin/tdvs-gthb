(function() {

	function controller(personDataService, UtilService, languageDataService, productDataService, 
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
			getPerson();
			getLanguages();
			getCategories();
		}

		/*Initial functions*/
		function getPerson() {
			function setEditTexts(type) {
				switch(type) {
					case 2: 
						vm.categories_text = "Choose your field(s) of work";
						vm.biography_text = "Brand statement / biography";
						vm.resume_text = "Resume or brand presentation";
						vm.resume_sub_text = "Even more things to tell your customers? Upload it here."
						break;
					default:
						vm.categories_text = "Choose your field(s) of expertise";
						vm.biography_text = "Tell us more about yourself";
						vm.resume_text = "Resume or presentation";
						vm.resume_sub_text = "Even more ways to tell your story."
						break;
				}
			}

			function onGetProfileSuccess(data) {
				vm.person = angular.copy(data);
				vm.person_original = angular.copy(data);
				vm.images = UtilService.parseImagesUrl(vm.person.media.photos, vm.person.url_images);
				vm.curriculum = currentHost() + vm.person.url_images + vm.person.curriculum;
				setEditTexts(data.type[0])
			}

			personDataService.getProfile({
				personId: person.short_id
			}, onGetProfileSuccess, UtilService.onError);
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
			var media = vm.person.media;
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
					person_id: person.short_id,
					file: file
				}
				Upload.upload({
					url: personDataService.Uploads,
					data: data
				}).then(function (dataUpload) {
					//if uplading crop, replace it
					if(index!==null && index>-1) {
						vm.person.media.photos[index] = dataUpload.data.filename;
					} else {
						//if not, add it and crop it
						vm.person.media.photos.unshift(dataUpload.data.filename);
						var imageToCrop = currentHost() + vm.person.url_images + dataUpload.data.filename;
						openCropModal(imageToCrop, 0);
					}
					vm.images = UtilService.parseImagesUrl(vm.person.media.photos, vm.person.url_images);
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
				vm.person.media = parsePhotos();
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
				person_id: person.short_id,
				file: file
			}
			Upload.upload({
				url: personDataService.Uploads,
				data: data
			}).then(function (dataCV) {
				vm.person.curriculum = dataCV.data.filename;
			}, function (err) {
				//errors
			})
		}

		function deleteCV() {
			vm.person.curriculum = '';
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
			vm.person.media = parsePhotos();
		}

		function canceled(){
			vm.images = dragndropService.canceled();
		}

		function save() {
			function parseTags(value) {
				return value.replace(/<[^\/>][^>]*><\/[^>]+>/gim, "");
			}

			function onUpdateProfileSuccess(data) {
				UtilService.setLeavingModal(false);
				$window.location.href = vm.person.about_link;
			}

			var data = {}
			for(var key in vm.person) {
				if(key === 'text_biography') {
					for(var language in vm.person[key]) {
						vm.person[key][language] = UtilService.stripHTMLTags(vm.person[key][language]);
					}
				}
				if(key !== 'account_state')
					data[key] = angular.copy(vm.person[key]);
			}

			personDataService.updateProfile(data, {
				personId: person.short_id
			}, onUpdateProfileSuccess, UtilService.onError);
		}

		//events
		$scope.$on(deviserEvents.make_profile_public_errors, function(event, args) {
			//set form submitted
			vm.form.$setSubmitted();
			//set fields
			if(args.required_fields && args.required_fields.length > 0) {
				args.required_fields.forEach(function(element) {
					if(element === 'photos')
						vm.setPhotosRequired = true;
					if(element === 'text_biography')
						vm.setBiographyRequired = true;
					if(element === 'categories')
						vm.setCategoriesRequired = true;
				});
			}
		});

		//watches
		$scope.$watch('editAboutCtrl.person', function (newValue, oldValue) {
			if(newValue) {
				if(!angular.equals(newValue, vm.person_original)) {
					UtilService.setLeavingModal(true);
				} else {
					UtilService.setLeavingModal(false);
				}
			}
}, true);


	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/person/edit-about/edit-about.html',
		controller: controller,
		controllerAs: 'editAboutCtrl',
	}

	angular.module('todevise')
		.component('editAbout', component);

}())