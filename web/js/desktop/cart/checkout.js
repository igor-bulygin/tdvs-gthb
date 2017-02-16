(function () {
	"use strict";

	function controller(cartDataService, tagDataService, productDataService, UtilService, cartService) {
		var vm = this;
		vm.cart_state = {
			state: 1
		};

		init();

		function init() {
			getCart();
		}

		function createCart() {
			function onCreateCartSuccess(data) {
				UtilService.setLocalStorage('cart_id', data.id);
			}
			function onCreateCartError(err) {
				console.log(err);
			}
			cartDataService.createCart(onCreateCartSuccess, onCreateCartError)
		}


		function getCart() {
			var cart_id = UtilService.getLocalStorage('cart_id');

			function onGetCartSuccess(data) {
				vm.cart = angular.copy(data);
				cartService.parseTags(vm.cart);
				vm.cart.products.forEach(function(product) {
					product.link = currentHost() + '/work/' + product.product_slug + '/' + product.product_id;
				});
				vm.devisers = cartService.parseDevisersFromProducts(vm.cart);
			}

			function onGetCartError(err) {
				createCart();
				console.log(err);
			}

			if(cart_id) {
				cartDataService.getCart({id: cart_id}, onGetCartSuccess, onGetCartError);
			} else {
				createCart();
			}
		}
	}

	angular.module('todevise')
		.controller('checkoutCtrl', controller);

}());