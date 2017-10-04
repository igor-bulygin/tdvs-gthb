(function () {
	"use strict";

	function controller($timeout) {
		var vm = this;
		vm.isOpen=true;

	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/cart/cart-panel/cart-panel.html',
		controller: controller,
		controllerAs: 'cartPanelCtrl',
		bindings: {
			packs: '<',
			total:'<'
		}
	}

	angular
		.module('cart')
		.component('cartPanel', component);

}());