(function () {
	"use strict";

	function controller(deviserDataService, Upload, toastr, UtilService, $timeout) {
		var vm = this;
		vm.upload = upload;
		vm.update = update;
		vm.deleteImage = delete_image;
		vm.dragOver = dragOver;
		vm.dragStart = dragStart;
		vm.drop = drop;

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
					update();
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
			update();
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
				if(index < vm.original_index)
					vm.images[vm.original_index] = vm.images[vm.original_index-1];
				//insert image in index
				vm.images.splice(index, 0, vm.image_being_moved);
				//set previous_index
				vm.previous_index = index;
			} else {
				//set previous index
				vm.previous_index = index;
			}
			return true;
		}

		function drop(index) {
			var index_to_delete=0;
			if(index < vm.original_index)
				index_to_delete = vm.original_index + 1;
			else {
				index_to_delete = vm.original_index;
			}
			//update
			update(index_to_delete);
			//reset iteration
			delete vm.image_being_moved;
			delete vm.previous_index;


		}

		init();

	}

	angular.module('todevise')
		.controller('editPressCtrl', controller);

}());
