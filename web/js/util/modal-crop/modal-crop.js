(function () {
	"use strict";

	function controller() {
		var vm = this;
		vm.ok = ok;
		vm.dismiss = dismiss;
		
		function ok() {
			vm.close({$value:vm.resolve.photoCropped})
		}
		
		function dismiss(){
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