(function () {
	"use strict";

	function controller(Upload, personDataService, productDataService, uploadDataService, UtilService) {
		var vm = this;
		vm.ok = ok;
		vm.dismiss = dismiss;
		var data = {
			person_id: vm.resolve.person.id || vm.resolve.person.short_id
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
				vm.size = {
					w: 500,
					h: 500
				};
				break;
			}
		}

		init();

		function ok() {
			function onUploadFileSuccess(data) {
				vm.disableApply = false;
				vm.close({
					$value: data
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
				case "work_photo":
					data.deviser_id = data.person_id;
					if(vm.resolve.product_id) {
						data.type = "known-product-photo";
						data.product_id = vm.resolve.product_id;
					}
					else {
						data.type = 'unknown-product-photo';
					}
				default:
					break;
			}
			uploadDataService.UploadFile(data, onUploadFileSuccess, onUploadFileError, console.log);
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