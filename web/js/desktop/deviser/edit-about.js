(function () {
	"use strict";



	function controller(deviserDataService, UtilService, languageDataService, toastr, productDataService, Upload, $timeout, $rootScope, $scope, deviserEvents, $uibModal) {
		var vm = this;
		vm.uploadPhoto = uploadPhoto;
		vm.openCropModal = openCropModal;
		vm.uploadCV = uploadCV;
		vm.deleteCV = deleteCV;
		vm.deleteImage = delete_image;
		vm.dragOver = dragOver;
		vm.dragStart = dragStart;
		vm.drop = drop;
		vm.checkPhotos = checkPhotos;
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
					uploadPhoto([Upload.dataUrltoBlob(imageCropped, "temp.png")], null, index, false);
				}
			}, function (err) {
				console.log(err);
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
					toastr.success("Photo uploaded!");
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
					toastr.error(err);
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

		function dragStart(event, index) {
			vm.original_index = index;
			vm.original_images = angular.copy(vm.images);
			vm.image_being_moved = vm.images[index];
		}

		function dragOver(event, index) {
			if(vm.previous_index) {
				//get original images
				vm.images = angular.copy(vm.original_images);
				if(index < vm.original_index) {
					vm.images[vm.original_index] = vm.images[vm.original_index-1];
				}
				//insert image in index position
				vm.images.splice(index, 0, vm.image_being_moved);
				//set previous index
				vm.previous_index = index;
			} else {
				//set previous index
				vm.previous_index = index;
			}
			return true;
		}

		function drop(index) {
			var index_to_delete = 0;
			if(index < vm.original_index)
				index_to_delete = vm.original_index + 1;
			else {
				index_to_delete = vm.original_index;
			}
			//update
			vm.images.splice(index_to_delete, 1);
			vm.deviser.media = parsePhotos();
		}

		function checkPhotos(){
			if(vm.images.length >= 5) {
				vm.showMaxPhotosLimit = true;
			}
			else {
				vm.showMaxPhotosLimit = false;
			}
		}

		//watches
		$scope.$watch('editAboutCtrl.deviser', function (newValue, oldValue) {
			if(newValue) {
				if(!angular.equals(newValue, vm.deviser_original) && (vm.images.length >=3 && vm.images.length <=5) ) {
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