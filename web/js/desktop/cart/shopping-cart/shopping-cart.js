(function () {
	"use strict";

	function controller(cartDataService, UtilService, cartService) {
		var vm = this;
		vm.deleteItem = deleteItem;
		vm.isObject = UtilService.isObject;
		var cart_id = UtilService.getLocalStorage('cart_id');

		function deleteItem(price_stock_id) {
			cartDataService.CartProduct.delete({
				id: cart_id,
				productId: price_stock_id
			}).$promise.then(function(deletedData) {
				vm.cart = angular.copy(deletedData);
				vm.devisers = cartService.parseDevisersFromProducts(vm.cart);
				cartService.parseTags(vm.cart);
			});
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