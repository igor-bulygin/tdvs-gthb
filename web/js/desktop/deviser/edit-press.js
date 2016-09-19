(function () {
	"use strict";

	function controller($scope, deviserDataService, Upload, toastr, UtilService) {
		var vm = this;
		vm.upload = upload;
		vm.update = update;
		vm.deleteImage = delete_image;

		function getDeviser() {
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function (dataDeviser) {
				vm.deviser = dataDeviser;
				if (!vm.deviser.press)
					vm.deviser.press = [];
				vm.images = UtilService.parseImagesUrl(vm.deviser.press, vm.deviser.url_images);
			}, function (err) {
				toastr.error(err);
			});
		}

		function init() {
			getDeviser();
		}

		function update(index) {
			if (index >= 0) {
				vm.images.splice(index, 1);
			}
			var patch = new deviserDataService.Profile;
			patch.scenario = "deviser-update-profile";
			patch.press = [];
			patch.deviser_id = vm.deviser.id;
			vm.images.forEach(function (element) {
				patch.press.push(element.filename);
			});
			patch.$update().then(function (dataPress) {
				//console.log("dataPress", dataPress);
			}, function (err) {
				toastr.error(err);
			});
		}

		function upload(images, errImages) {
			vm.files = images;
			vm.errFiles = errImages;
			angular.forEach(vm.files, function (file) {
				var data = {
					type: "deviser-press",
					deviser_id: vm.deviser.id,
					file: file
				};
				Upload.upload({
					url: deviserDataService.Uploads,
					data: data
				}).then(function (dataUpload) {
					toastr.success("Photo uploaded!");
					vm.deviser.press.unshift(dataUpload.data.filename);
					vm.images = UtilService.parseImagesUrl(vm.deviser.press, vm.deviser.url_images);
					update();
				}, function (err) {
					toastr.error(err);
				}, function (evt) {
					file.progress = parseInt(100.0 * evt.loaded / evt.total);
					console.log('progress: ' + file.progress + '% ' + evt.config.data.file.name);
				});
			});
		}

		function delete_image(index) {
			vm.images.splice(index, 1);
			update();
		}

		init();

	}

	angular.module('todevise', ['api', 'ngFileUpload', 'dndLists', 'toastr', 'util'])
		.controller('editPressCtrl', controller);

}());