(function () {
	"use strict";

	function controller(deviserDataService, Upload, toastr, UtilService, $timeout) {
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
				getDeviser();
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
		}

		function dragStart(event, index) {
			vm.original_index = index;
			vm.original_images = angular.copy(vm.images);
			vm.image_being_moved = vm.images[index];
		}

		function dragOver(event, index) {
			//copy original images
			vm.images = angular.copy(vm.original_images);
			//get index where it will drop
			vm.previous_index = index;
			//if position is after original index, insert
			if(vm.previous_index > vm.original_index) {
				vm.images.splice(vm.previous_index, 0, vm.image_being_moved)
			} else {
			//if not, change image in original index to the image before it and then add image being moved
			vm.images[vm.original_index] = vm.original_images[vm.original_index-1];
			vm.images.splice(vm.previous_index, 0, vm.image_being_moved);
			}
			return true;
		}

		function moved(index) {
			vm.images = angular.copy(vm.original_images);
			if(vm.previous_index > vm.original_index) {
				vm.images.splice(vm.previous_index, 0, vm.image_being_moved)
				vm.images.splice(vm.original_index, 1)
			} else {
				vm.images.splice(vm.original_index, 1);
				vm.images.splice(vm.previous_index, 0, vm.image_being_moved);
			}
			//reset iteration
			delete vm.image_being_moved;
			delete vm.previous_index;
		}

		function canceled(event, index) {
			vm.images = angular.copy(vm.original_images);
		}

		function done() {
			update();
		}

		init();

	}

	angular.module('todevise')
		.controller('editPressCtrl', controller);

}());
