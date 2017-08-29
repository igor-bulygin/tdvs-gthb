(function () {
	"use strict";

	function controller(boxDataService, UtilService, $window) {
		var vm = this;
		vm.createBox = createBox;
		vm.has_error = UtilService.has_error;

		function createBox(form) {
			function onCreateBoxSuccess(data){
				vm.close();
				$window.location.href = data.link;
			}

			form.$setSubmitted();
			if(form.$valid) {
				boxDataService.createBox(vm.box, onCreateBoxSuccess, UtilService.onError);
			}
		}
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/box/modal-create-box/modal-create-box.html',
		controller: controller,
		controllerAs: 'modalCreateBoxCtrl',
		bindings: {
			dismiss: '&',
			close: '&'
		}
	}

	angular
		.module('box')
		.component('modalCreateBox', component);

}());