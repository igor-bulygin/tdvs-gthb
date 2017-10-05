(function () {
	"use strict";

	function controller($timeout) {
		var vm = this;
		vm.isOpen=false;
		init();

		function init() {
			$timeout(function() { vm.isOpen=true; }, 1000);
			
		}

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