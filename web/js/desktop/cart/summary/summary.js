(function () {
	"use strict";

	function controller() {
		var vm = this;
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