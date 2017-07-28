(function () {
	'use strict';

	function controller($rootScope, cartEvents) {
		var vm = this;
		vm.setShippingPriceTime = setShippingPriceTime;
		vm.isObject = angular.isObject;
		vm.save = save;

		init();

		function init() {
		}

		function setShippingPriceTime(pack) {
			switch(pack.shipping_type) {
				case 'standard':
					pack.shipping_time = pack.deviser_info.shipping_time;
					pack.shipping_price = pack.deviser_info.price;
					break;
				case 'express':
					pack.shipping_time = pack.deviser_info.shipping_express_time;
					pack.shipping_price = pack.deviser_info.price_express;
					break;
				default:
					pack.shipping_time = pack.deviser_info.shipping_time;
					pack.shipping_price = pack.deviser_info.price;
					break;
			}
			$rootScope.$broadcast(cartEvents.cartUpdated, {cart: vm.cart});
		}

		function save() {
			vm.state = 3;
		}
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/cart/shipping-methods/shipping-methods.html',
		controller: controller,
		controllerAs: 'shippingMethodsCtrl',
		bindings: {
			state: '=?',
			cart: '<'
		}
	}

	angular
		.module('cart')
		.component('shippingMethods', component);

}());