(function () {
	"use strict";

	function controller() {
		var vm = this;
		vm.ok = ok;
		vm.closeModal = closeModal;
		vm.title_language = 'en-US';
		vm.description_language = 'en-US';
		vm.imageData = {};

		function ok() {
			vm.close({
				$value: vm.imageData
			});
		}

		function closeModal() {
			vm.dismiss();
		}

	}

	var component = {
		templateUrl: currentHost() + '/js/util/modal-crop-description/modal-crop-description.html',
		controller: controller,
		controllerAs: 'modalCropDescriptionCtrl',
		bindings: {
			resolve: '<',
			close: '&',
			dismiss: '&',
			modalInstance: '<'
		}
	}

	angular
		.module('util')
		.component('modalCropDescription', component)

}());