(function () {
	"use strict";

	function controller(UtilService, $uibModal) {
		var vm = this;
		vm.openCreateBoxModal = openCreateBoxModal;

		function openCreateBoxModal() {
			var modalInstance = $uibModal.open({
				component: 'modalCreateBox',
				size: 'sm',
			});
		}

	}

	angular.module('box')
		.controller('viewBoxesCtrl', controller);

}());