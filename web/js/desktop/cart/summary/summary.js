(function () {
	"use strict";

	function controller(UtilService) {
		var vm = this;
		vm.isObject = UtilService.isObject;
		vm.proceedToCheckout = proceedToCheckout;

		function proceedToCheckout() {
			vm.state.state = 2;
		}

	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/cart/summary/summary.html',
		controller: controller,
		controllerAs: 'summaryCtrl',
		bindings: {
			state: '<',
			cart: '<',
			devisers: '<'
		}
	}

	angular
		.module('todevise')
		.component('cartSummary', component);

}());