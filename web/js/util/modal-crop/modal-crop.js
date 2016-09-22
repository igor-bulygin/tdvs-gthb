(function () {
	"use strict";

	function controller() {
		var vm = this;
		vm.ok = ok;
		vm.dismiss = dismiss;


		function init() {
			switch (vm.resolve.type) {
			case "header":
				vm.area_type = 'rectangle';
				vm.width = 1280;
				vm.height = 450;
				vm.aspect_ratio = 2.8;
				break;
			case "profile":
				vm.area_type = 'circle';
				vm.width = 340;
				vm.height = 340;
				vm.aspect_ratio = 1;
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