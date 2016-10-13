(function () {
	"use strict";



	function controller(deviserDataService, UtilService, languageDataService, toastr, productDataService, Upload, $timeout, $rootScope, $scope, deviserEvents, $uibModal) {
		var vm = this;
		vm.update = update;
		vm.move = move;
		vm.uploadPhoto = uploadPhoto;
		vm.openCropModal = openCropModal;
		vm.uploadCV = uploadCV;
		vm.deleteCV = deleteCV;
		vm.deleteImage = delete_image;
		vm.biography_language = "en-US";

		function getDeviser() {
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function (dataDeviser) {
				vm.deviser = dataDeviser;
				vm.deviser_original = angular.copy(dataDeviser);
				vm.images = UtilService.parseImagesUrl(vm.deviser.media.photos, vm.deviser.url_images);
				vm.curriculum = currentHost() + vm.deviser.url_images + vm.deviser.curriculum;
			}, function (err) {
				toastr.error(err);
			});
		}

		function getCategories() {
			productDataService.Categories.get()
				.$promise.then(function (dataCategories) {
					vm.categories = dataCategories.items;
				}, function (err) {
					toastr.error(err);
				});
		}

		function getLanguages() {
			languageDataService.Languages.get()
				.$promise.then(function (dataLanguages) {
					vm.languages = dataLanguages.items;
				}, function (err) {
					toastr.error(err);
				});
		}

		function init() {
			getDeviser();
			getLanguages();
			getCategories();
		}

		init();

		function parsePhotos() {
			var media = vm.deviser.media;
			media.photos = [];
			vm.images.forEach(function (element) {
				if(media.photos.indexOf(element.filename) < 0)
					media.photos.push(element.filename);
			});
			return media;
		}

		function move(index) {
			if(index > -1) {
				vm.images.splice(index,1);
				vm.deviser.media = parsePhotos();
			} else {
				toastr.error("Could not move.");
			}
		}

		function update(key, value) {
			if(key === 'text_biography') {
				for(var language in value) {
					value[language]=value[language].replace(/<[^\/>][^>]*><\/[^>]+>/gim, "");
				}
			}
			var patch = new deviserDataService.Profile;
			patch.scenario = 'deviser-update-profile';
			patch.deviser_id = vm.deviser.id;
			patch[key] = value;
			patch.$update().then(function(dataUpdate) {
				$rootScope.$broadcast('update-profile');
			}, function (err) {
				for(var key in err.data.errors) {
					toastr.error(err.data.errors[key]);
				}
			})
		}

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
				toastr.error(err);
			})
		}

		function deleteCV() {
			vm.deviser.curriculum = '';
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
					uploadPhoto([Upload.dataUrltoBlob(imageCropped, "temp.png")], null, index);
				}
			}, function (err) {
				console.log(err);
			});

		}

		function uploadPhoto(images, errImages, index) {
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
					toastr.success("Photo uploaded!");
					if(index>-1) {
						vm.deviser.media.photos[index] = dataUpload.data.filename;
					} else {
						vm.deviser.media.photos.unshift(dataUpload.data.filename);
					}
						vm.images = UtilService.parseImagesUrl(vm.deviser.media.photos, vm.deviser.url_images);
					$timeout(function () {
						delete file.progress;
					}, 1000);
				}, function (err) {
					toastr.error(err);
				}, function (evt) {
					//progress
					file.progress = parseInt(100.0 * evt.loaded / evt.total);
				});
			})
		}

		function delete_image(index) {
			if (vm.images.length > 3) {
				vm.images.splice(index, 1);
				vm.deviser.media = parsePhotos();
			} else {
				toastr.error("Must have between 3 and 5 photos.");
			}
		}



		//watches
		$scope.$watch('editAboutCtrl.deviser', function (newValue, oldValue) {
			if(newValue) {
				if(!angular.equals(newValue, vm.deviser_original)) {
					$rootScope.$broadcast(deviserEvents.deviser_changed, {value: true, deviser: newValue});
				} else {
					$rootScope.$broadcast(deviserEvents.deviser_changed, {value: false});
				}
			}
		}, true);

		//events
		$scope.$on(deviserEvents.deviser_updated, function(event, args) {
			getDeviser();
		});

		$scope.$on(deviserEvents.deviser_changed, function(event, args) {
			if(args.deviser)
				vm.deviser = angular.copy(args.deviser);
		});

		$scope.$on(deviserEvents.make_profile_public_errors, function(event, args) {
			//set form submitted
			vm.form.$setSubmitted();
			//set fields
			for(var i=0; i<args.required_fields.length; i++) {
				if(args.required_fields[i]==='photos') {
					vm.setPhotosRequired = true;
				}
				if(args.required_fields[i]==='biography') {
					vm.setBiographyRequired = true;
				}
				if(args.required_fields[i]==='categories') {
					vm.setCategoriesRequired = true;
				}
			}
		});

	}

	angular
		.module('todevise')
		.controller('editAboutCtrl', controller);
}());