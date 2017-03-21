(function () {
	"use strict";

	function controller(boxDataService, UtilService, $window) {
		var vm = this;
		vm.createBox = createBox;

		function createBox(form) {
			function onCreateBoxSuccess(data){
				//go to the box
				vm.close();
				$window.location.href = data.link;
			}

			form.$setSubmitted();
			if(form.$valid) {
				boxDataService.createBox(vm.box, onCreateBoxSuccess, UtilService.onError);
			} else {
				//TODO: show errors on form
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