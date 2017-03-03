(function () {
	"use strict";

	function controller(personDataService, Upload, toastr, UtilService, $timeout, $window, dragndropService) {
		var vm = this;
		vm.upload = upload;
		vm.update = update;
		vm.deleteImage = delete_image;
		vm.dragOver = dragOver;
		vm.dragStart = dragStart;
		vm.moved = moved;
		vm.canceled = canceled;
		vm.done = done;

		init();

		function init() {
			getPerson();
		}

		function getPerson() {
			function onGetProfileSuccess(data) {
				vm.person = angular.copy(data);
				if(!vm.person.press)
					vm.person.press = [];
				vm.images = UtilService.parseImagesUrl(vm.person.press, vm.person.url_images);
			}

			personDataService.getProfile({
				personId: person.short_id
			}, onGetProfileSuccess, UtilService.onError);
		}

		function update() {
			function onUpdateProfileSuccess(data) {
				$window.location.href = vm.person.press_link;
			}

			var data = {
				press: []
			}
			vm.images.forEach(function (element) {
				if(data.press.indexOf(element.filename)<0)
					data.press.push(element.filename);
			});

			personDataService.updateProfile(data, {
				personId: person.short_id
			}, onUpdateProfileSuccess, UtilService.onError);
		}

		function upload(images, errImages) {
			vm.files = images;
			vm.errFiles = errImages;
			angular.forEach(vm.files, function (file) {
				var data = {
					type: "deviser-press",
					person_id: person.short_id,
					file: file
				};
				Upload.upload({
					url: personDataService.Uploads,
					data: data
				}).then(function (dataUpload) {
					vm.person.press.unshift(dataUpload.data.filename);
					vm.images = UtilService.parseImagesUrl(vm.person.press, vm.person.url_images);
					$timeout(function () {
						delete file.progress;
					}, 1000);
				}, function (err) {
					UtilService.onError(err);
				}, function (evt) {
					file.progress = parseInt(100.0 * evt.loaded / evt.total);
				});
			});
		}

		function delete_image(index) {
			vm.images.splice(index, 1);
			vm.person.press.splice(index, 1);
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
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/person/edit-press/edit-press.html',
		controller: controller,
		controllerAs: 'editPressCtrl'
	}

	angular
		.module('todevise')
		.component('editPress', component);

}());