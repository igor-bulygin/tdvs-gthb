(function () {
	"use strict";

	function controller() {
		var vm = this;
		vm.ok = ok;
		vm.dismiss = dismiss;


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
			default: 
				vm.area_type = 'rectangle';
				vm.aspect_ratio = 1;
				vm.size = 'max';
				break;
			}
		}

		init();

		function ok() {
			vm.close({
				$value: vm.photoCropped
			});
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