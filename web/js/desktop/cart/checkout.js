(function () {
	"use strict";

	function controller(cartDataService, tagDataService, productDataService, UtilService, cartService, localStorageUtilService) {
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
				localStorageUtilService.setLocalStorage('cart_id', data.id);
			}

			cartDataService.createCart(onCreateCartSuccess, UtilService.onError);
		}


		function getCart() {
			var cart_id = localStorageUtilService.getLocalStorage('cart_id');

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
				UtilService.onError(err);
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