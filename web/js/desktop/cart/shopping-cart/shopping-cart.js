(function () {
	"use strict";

	function controller() {
		var vm = this;
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/cart/shopping-cart/shopping-cart.html',
		controller: controller,
		controllerAs: 'shoppingCartCtrl',
		bindings: {
			cart: '<',
			devisers: '<'
		}
	}

	angular
		.module('todevise')
		.component('shoppingCart', component);

}());