(function () {
	"use strict";

	function controller($timeout) {
		var vm = this;
		vm.isOpen=false;
		init();

		function init() {
			vm.total = 0;
			vm.packs.forEach(function(pack){
				vm.total += pack.pack_price;
			});
			$timeout(function() { vm.isOpen=true; }, 1000);
			
		}

	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/cart/cart-panel/cart-panel.html',
		controller: controller,
		controllerAs: 'cartPanelCtrl',
		bindings: {
			packs: '<'
		}
	}

	angular
		.module('cart')
		.component('cartPanel', component);

}());