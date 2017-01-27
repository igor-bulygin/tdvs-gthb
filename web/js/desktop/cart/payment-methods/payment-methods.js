(function () {
	"use strict";

	function controller() {
		var vm = this;
		vm.editPersonalInfo = editPersonalInfo;

		init();

		function init(){

		}

		function editPersonalInfo() {
			vm.state.state = 2;
		}

	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/cart/payment-methods/payment-methods.html',
		controller: controller,
		controllerAs: 'paymentMethodsCtrl',
		bindings: {
			state: '<',
			cart: '<'
		}
	}

	angular
		.module('todevise')
		.component('paymentMethods', component);

}());