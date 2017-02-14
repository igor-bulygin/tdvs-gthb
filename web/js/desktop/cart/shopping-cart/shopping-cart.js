(function () {
	"use strict";

	function controller(cartDataService, UtilService, cartService, cartEvents, $rootScope) {
		var vm = this;
		vm.deleteItem = deleteItem;
		vm.isObject = UtilService.isObject;
		var cart_id = UtilService.getLocalStorage('cart_id');

		function deleteItem(price_stock_id) {
			function onDeleteItemSuccess(data) {
				vm.cart = angular.copy(data);
				vm.devisers = cartService.parseDevisersFromProducts(vm.cart);
				cartService.parseTags(vm.cart);
				$rootScope.$broadcast(cartEvents.cartUpdated, {cart: vm.cart});
			}

			function onDeleteItemError(err){
				console.log(err);
			}

			cartDataService.deleteItem({
				id: cart_id,
				productId: price_stock_id
			}, onDeleteItemSuccess, onDeleteItemError);
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
		.module('todevise')
		.component('shoppingCart', component);

}());