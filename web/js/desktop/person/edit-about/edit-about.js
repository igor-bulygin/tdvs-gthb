(function() {

	function controller(personDataService, UtilService, languageDataService, productDataService, 
		Upload, uploadDataService, $timeout, $rootScope, $scope, deviserEvents, $uibModal, $window) {

		var vm = this;
		vm.stripHTMLTags = UtilService.stripHTMLTags;
		vm.save = save;
		vm.uploadPhoto = uploadPhoto;
		vm.openCropModal = openCropModal;
		vm.uploadCV = uploadCV;
		vm.deleteCV = deleteCV;
		vm.deleteImage = delete_image;
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
				vm.isDraft = (vm.person.account_state === 'draft') ? true : false;
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
					},
					person: function() {
						return vm.person;
					},
					index: function() {
						return index;
					},
					type: function() {
						return "deviser-photos";
					}
				}
			});

			modalInstance.result.then(function (data) {
				if(index !== null && index >-1) {
					vm.person.media.photos[index] = data.data.filename;
					vm.images = UtilService.parseImagesUrl(vm.person.media.photos, vm.person.url_images);
				}
				
			}, function (err) {
				UtilService.onError(err);
			});

		}

		function uploadPhoto(images, errImages, index, cropOption) {
			function onUploadPhotoSuccess(data, file) {
				$timeout(function() {
					delete file.progress;
				}, 1000);
				vm.person.media.photos.unshift(data.data.filename);
				var imageToCrop = currentHost() + vm.person.url_images + data.data.filename;
				openCropModal(imageToCrop, 0);
				//vm.images = UtilService.parseImagesUrl(vm.person.media.photos, vm.person.url_images);
			}

			function onWhileUploading(evt, file) {
				file.progress = parseInt(100.0 * evt.loaded / evt.total);
			}

			vm.files = images;
			vm.errFiles = errImages;
			angular.forEach(vm.files, function (file) {
				var data = {
					type: "deviser-media-photos",
					person_id: person.short_id,
					file: file
				}
				uploadDataService.UploadFile(data, 
					function(data) {
						return onUploadPhotoSuccess(data, file)}, 
					UtilService.onError, 
					function(evt){
						return onWhileUploading(evt, file);
					});
			});
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
			function onUploadCVSuccess(data) {
				vm.person.curriculum = data.data.filename;
			}

			var data = {
				type: 'deviser-curriculum',
				person_id: person.short_id,
				file: file
			}

			uploadDataService.UploadFile(data, onUploadCVSuccess, UtilService.onError, console.log)
		}

		function deleteCV() {
			vm.person.curriculum = '';
		}

		function save() {
			function parseTags(value) {
				return value.replace(/<[^\/>][^>]*><\/[^>]+>/gim, "");
			}

			function onUpdateProfileSuccess(data) {
				UtilService.setLeavingModal(false);
				var newObject = {
					categories: vm.person.categories || [],
					curriculum: vm.person.curriculum || null,
					media: {
						photos: vm.person.media.photos || []
					},
					text_biography: vm.person.text_biography || {},
				}
				$rootScope.$broadcast(deviserEvents.updated_deviser, newObject);
				$window.location.href = vm.person.about_link;
			}

			var data = {}
			for(var key in vm.person) {
				if(key !== 'account_state')
					data[key] = angular.copy(vm.person[key]);
				if(key === 'text_biography') {
					data[key] = {}
					for(var language in vm.person[key]) {
						data[key][language] = parseTags(vm.person[key][language]);
					}
				}
			}

			personDataService.updateProfile(data, {
				personId: person.short_id
			}, onUpdateProfileSuccess, UtilService.onError);
		}

		//events
		$scope.$on(deviserEvents.updated_deviser, function(event, args) {
			vm.person_original = Object.assign(vm.person, args);
			vm.person = Object.assign(vm.person, args);
			UtilService.setLeavingModal(false);
		})

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