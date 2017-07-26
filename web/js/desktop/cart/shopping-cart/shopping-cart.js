(function () {
	"use strict";

	function controller(cartDataService, UtilService, cartService, cartEvents, $rootScope, localStorageUtilService) {
		var vm = this;
		vm.deleteItem = deleteItem;
		vm.addQuantity = addQuantity;
		vm.subQuantity = subQuantity;
		vm.isObject = UtilService.isObject;
		var cart_id = localStorageUtilService.getLocalStorage('cart_id');

		function addQuantity(product) {
			if(product.quantity < product.product_info.stock)
				addProduct(product, 1);
		}

		function subQuantity(product) {
			if(product.quantity === 1)
				deleteItem(product.price_stock_id);
			else {
				addProduct(product, -1)
			}
		}

		function addProduct(product, quantity) {
			function onAddProductSuccess(data) {
				vm.cart = angular.copy(data);
				cartService.parseTags(vm.cart);
			}

			cartDataService.addProduct({
				product_id: product.product_id,
				price_stock_id: product.price_stock_id,
				quantity: quantity
			}, {
				id: cart_id
			}, onAddProductSuccess, UtilService.onError);
		}

		function deleteItem(price_stock_id) {
			function onDeleteItemSuccess(data) {
				vm.cart = angular.copy(data);
				cartService.parseTags(vm.cart);
				$rootScope.$broadcast(cartEvents.cartUpdated, {cart: vm.cart});
			}

			cartDataService.deleteItem({
				id: cart_id,
				productId: price_stock_id,
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