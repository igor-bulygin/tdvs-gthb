(function () {
	"use strict";

	function config(nyaBsConfigProvider) {
		nyaBsConfigProvider.setLocalizedText('en-us', {
			defaultNoneSelection: 'Choose your field of work'
		});
	}

	function controller(deviserDataService, UtilService, languageDataService, toastr, productDataService, Upload, $timeout) {
		var vm = this;
		vm.update = update;
		vm.move = move;
		vm.uploadPhoto = uploadPhoto;
		vm.uploadCV = uploadCV;
		vm.deleteCV = deleteCV;
		vm.deleteImage = delete_image;
		vm.biography_language = "en-US";

		function getDeviser() {
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function (dataDeviser) {
				vm.deviser = dataDeviser;
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
				var media = parsePhotos();
				update('media', media);
			} else {
				toastr.error("Could not move.");
			}
		}

		function update(key, value) {
			var patch = new deviserDataService.Profile;
			patch.scenario = 'deviser-update-profile';
			patch.deviser_id = vm.deviser.id;
			patch[key] = value;
			patch.$update().then(function(dataUpdate) {
				getDeviser();
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
				update('curriculum', dataCV.data.filename);				
			}, function (err) {
				toastr.error(err);
			})
		}

		function deleteCV() {
			update('curriculum', '');			
		}

		function uploadPhoto(images, errImages) {
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
					vm.deviser.media.photos.unshift(dataUpload.data.filename);
					vm.images = UtilService.parseImagesUrl(vm.deviser.media.photos, vm.deviser.url_images);
					update('media', vm.deviser.media);
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
				var media = parsePhotos();				
				update('media', media);
			} else {
				toastr.error("Must have between 3 and 7 photos.");
			}
		}
	}

	angular
		.module('todevise')
		.config(config)
		.controller('editAboutCtrl', controller);
}());