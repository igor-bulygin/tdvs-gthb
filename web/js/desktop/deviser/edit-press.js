(function () {
	"use strict";

	function controller(deviserDataService, Upload, toastr, UtilService, $timeout, $window, dragndropService) {
		var vm = this;
		vm.upload = upload;
		vm.update = update;
		vm.deleteImage = delete_image;
		vm.dragOver = dragOver;
		vm.dragStart = dragStart;
		vm.moved = moved;
		vm.canceled = canceled;
		vm.done = done;

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

		function update() {
			var patch = new deviserDataService.Profile;
			patch.scenario = "deviser-update-profile";
			patch.press = [];
			patch.deviser_id = vm.deviser.id;
			vm.images.forEach(function (element) {
				if(patch.press.indexOf(element.filename)<0)
					patch.press.push(element.filename);
			});
			patch.$update().then(function (dataPress) {
				$window.location.href = '/deviser/' + dataPress.slug + '/' + dataPress.id + '/press';
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
					$timeout(function () {
						delete file.progress;
					}, 1000);
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
			vm.deviser.press.splice(index, 1);
		}

		function dragStart(index) {
			dragndropService.dragStart(index, vm.images);
		}

		function dragOver(index) {
			vm.images = dragndropService.dragOver(index, vm.images);
			return true;
		}

		function moved(index) {
			vm.images = dragndropService.moved(index)
		}

		function canceled() {
			vm.images = dragndropService.canceled();
		}

		function done() {
			update();
		}

		init();

	}

	angular.module('todevise')
		.controller('editPressCtrl', controller);

}());
