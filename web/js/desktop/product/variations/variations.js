(function () {
	"use strict";

	function controller() {
		var vm = this;
		vm.setMadeToOrder = setMadeToOrder;
		vm.preorder_selected = false;
		vm.made_to_order_selected = false;
		vm.bespoke_selected = false;
		vm.bespoke_language = 'en-US';

		function init(){

		}

		init();

		function setMadeToOrder(value) {
			vm.product['madetoorder']['type'] = value ? 1 : 0;
		}

		//watchs

		//events
		////TO DO: set bespoke text required if it is empty in english
		
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/product/variations/variations.html',
		controller: controller,
		controllerAs: 'productVariationsCtrl',
		bindings: {
			product: '<',
			languages: '='
		}
	}

	angular
		.module('todevise')
		.component('productVariations', component);
}());