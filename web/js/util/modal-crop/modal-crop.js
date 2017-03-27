(function () {
	"use strict";

	function controller(Upload, personDataService, UtilService) {
		var vm = this;
		vm.ok = ok;
		vm.dismiss = dismiss;
		var data = {
			person_id: vm.resolve.person.id
		};

		function init() {
			switch (vm.resolve.type) {
			case "header_cropped":
				vm.area_type = 'rectangle';
				vm.size = {
					w: 1280,
					h: 450
				};
				vm.aspect_ratio = 2.8;
				break;
			case "profile_cropped":
				vm.area_type = 'circle';
				vm.size = {
					w: 340,
					h: 340
				};
				vm.aspect_ratio = 1;
				break;
			case "work_photo":
				vm.area_type = 'rectangle';
				vm.aspect_ratio = 0.8;
				vm.size = 'max';
				break;
			default: 
				vm.area_type = 'rectangle';
				vm.aspect_ratio = 1;
				vm.size = 'max';
				break;
			}
		}

		init();

		function ok() {
			function onUploadFileSuccess(data) {
				vm.disableApply = false;
				vm.close({
					$value: data.data.filename
				})
			}

			function onUploadFileError(err) {
				UtilService.onError(err);
				vm.disableApply = false;
			}

			if(vm.resolve.index && vm.resolve.index >= 0)
				data.index = angular.copy(index);
			data.file = Upload.dataUrltoBlob(vm.photoCropped, "temp.png");
			switch (vm.resolve.type) {
				case "header_cropped":
					data.type = "deviser-media-header-cropped";
					break;
				case "profile_cropped":
					data.type = "deviser-media-profile-cropped";
					break;
				case "deviser-photos":
					data.type = "deviser-media-photos";
					break;
				default:
					break;
			}
			personDataService.UploadFile(data, onUploadFileSuccess, onUploadFileError, console.log);
		}

		function dismiss() {
			vm.close();
		}
	}

	var component = {
		templateUrl: currentHost() + '/js/util/modal-crop/modal-crop.html',
		controller: controller,
		controllerAs: 'modalCropCtrl',
		bindings: {
			resolve: '<',
			close: '&',
			dismiss: '&'
		}
	}

	angular
		.module('util')
		.component('modalCrop', component);

}());