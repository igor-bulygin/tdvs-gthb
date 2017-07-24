(function () {
	"use strict";

	function controller(cartDataService, UtilService, cartService, cartEvents, $rootScope, localStorageUtilService) {
		var vm = this;
		vm.deleteItem = deleteItem;
		vm.isObject = UtilService.isObject;
		var cart_id = localStorageUtilService.getLocalStorage('cart_id');

		function deleteItem(price_stock_id) {
			function onDeleteItemSuccess(data) {
				vm.cart = angular.copy(data);
				cartService.parseTags(vm.cart);
				$rootScope.$broadcast(cartEvents.cartUpdated, {cart: vm.cart});
			}

			cartDataService.deleteItem({
				id: cart_id,
				productId: price_stock_id
			}, onDeleteItemSuccess, UtilService.onError);
		}
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/cart/shopping-cart/shopping-cart.html',
		controller: controller,
		controllerAs: 'shoppingCartCtrl',
		bindings: {
			state: '<',
			cart: '<',
			devisers: '<'
		}
	}

	angular
		.module('cart')
		.component('shoppingCart', component);

}());