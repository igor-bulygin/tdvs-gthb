(function () {
	"use strict";

	function controller(deviserDataService, UtilService, languageDataService, toastr, productDataService, Upload, $timeout, $rootScope, $scope, deviserEvents, $uibModal, dragndropService) {
		var vm = this;
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

		function getDeviser() {
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function (dataDeviser) {
				vm.deviser = dataDeviser;
				vm.deviser_original = angular.copy(dataDeviser);
				vm.images = UtilService.parseImagesUrl(vm.deviser.media.photos, vm.deviser.url_images);
				vm.curriculum = currentHost() + vm.deviser.url_images + vm.deviser.curriculum;
			}, function (err) {
				//errors
			});
		}

		function getCategories() {
			productDataService.Categories.get({scope: 'roots'})
				.$promise.then(function (dataCategories) {
					vm.categories = dataCategories.items;
				}, function (err) {
					//errors
				});
		}

		function getLanguages() {
			languageDataService.Languages.get()
				.$promise.then(function (dataLanguages) {
					vm.languages = dataLanguages.items;
				}, function (err) {
					//errors
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
				//errors
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