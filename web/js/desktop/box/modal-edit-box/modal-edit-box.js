(function () {
	"use strict";

	function controller(boxDataService, UtilService) {
		var vm = this;
		vm.saveBox = saveBox;

		function init() {
			vm.box = angular.copy(vm.resolve.box);
		}

		init();

		function saveBox(form) {
			function onSaveBoxSuccess(data) {
				vm.close({$value: data});
			}

			form.$setSubmitted();
			if(form.$valid) {
				boxDataService.updateBox(vm.resolve.box, {
					idBox: vm.box.id
				}, onSaveBoxSuccess, UtilService.onError);
			} //TODO: else, wrong form
		}
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/box/modal-edit-box/modal-edit-box.html',
		controller: controller,
		controllerAs: 'modalEditBoxCtrl',
		bindings: {
			resolve: '<',
			dismiss: '&',
			close: '&'
		}
	}

	angular
		.module('box')
		.component('modalEditBox', component);

}());