(function () {
	"use strict";

	function controller(personDataService, Upload, uploadDataService, toastr, UtilService, $timeout, $window, dragndropService) {
		var vm = this;
		vm.upload = upload;
		vm.update = update;
		vm.deleteImage = delete_image;
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
			function onUploadFileSuccess(data, file) {
				$timeout(function () {
					delete file.progress;
				}, 1000);
				vm.person.press.unshift(data.data.filename);
				vm.images = UtilService.parseImagesUrl(vm.person.press, vm.person.url_images);
			}

			function onWhileUploadingFile(evt, file) {
				file.progress = parseInt(100.0 * evt.loaded / evt.total);
			}

			vm.files = images;
			vm.errFiles = errImages;
			angular.forEach(vm.files, function (file) {
				var data = {
					type: "deviser-press",
					person_id: person.short_id,
					file: file
				};

				uploadDataService.UploadFile(data, 
					function(data) {
						return onUploadFileSuccess(data, file);
					}, UtilService.onError,
					function(evt) {
						return onWhileUploadingFile(evt, file);
					})
			});
		}

		function delete_image(index) {
			vm.images.splice(index, 1);
			vm.person.press.splice(index, 1);
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