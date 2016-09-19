(function () {
	"use strict";

	function controller(deviserDataService, UtilService, languageDataService, toastr, productDataService, Upload, $scope) {
		var vm = this;
		vm.update = update;
		vm.uploadPhoto = uploadPhoto;
		vm.deleteImage = delete_image;
		vm.biography_language = "en-US";

		function getDeviser() {
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function (dataDeviser) {
				vm.deviser = dataDeviser;
				vm.images = UtilService.parseImagesUrl(vm.deviser.media.photos, vm.deviser.url_images);
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

		$scope.$watch('editAboutCtrl.image', function (newValue, oldValue) {
			if (newValue)
				uploadPhoto(newValue);
		});

		function update(index) {
			if (index >= 0) {
				vm.images.splice(index, 1);
			}
			var patch = new deviserDataService.Profile;
			patch.scenario = "deviser-update-profile";
			patch.deviser_id = vm.deviser.id;
			patch.categories = vm.deviser.categories;
			patch.text_biography = vm.deviser.text_biography;
			patch.text_short_description = vm.deviser.text_short_description;
			patch.media = vm.deviser.media;
			patch.media.photos = [];
			vm.images.forEach(function (element) {
				patch.media.photos.push(element.filename);
			});
			patch.$update().then(function (dataUpdate) {
				toastr.success('Updated!');
			}, function (err) {
				toastr.error(err);
			});
		}

		function uploadPhoto(image) {
			var data = {
				type: "deviser-media-photos",
				deviser_id: vm.deviser.id,
				file: image
			}
			Upload.upload({
				url: deviserDataService.Uploads,
				data: data
			}).then(function (dataUpload) {
				toastr.success("Photo uploaded!");
				vm.deviser.media.photos.unshift(dataUpload.data.filename);
				vm.images = UtilService.parseImagesUrl(vm.deviser.media.photos, vm.deviser.url_images);
				update();
			}, function (err) {
				toastr.error(err);
			}, function (evt) {
				//progress
				var progress = parseInt(100.0 * evt.loaded / evt.total);
			});
		}

		function delete_image(index) {
			vm.images.splice(index, 1);
			update();
		}



	}


	angular
		.module('todevise', ['api', 'util', 'toastr', 'nya.bootstrap.select', 'textAngular', 'ngFileUpload', 'dndLists'])
		.controller('editAboutCtrl', controller);
}());