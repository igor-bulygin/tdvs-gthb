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

		function getCart() {
			var cart_id = UtilService.getLocalStorage('cart_id');
			if(cart_id) {
				cartDataService.Cart.get({
					id: cart_id
				}).$promise.then(function (cartData) {
					vm.cart = angular.copy(cartData);
					cartService.parseTags(vm.cart);
					vm.cart.products.forEach(function(product) {
						console.log(product);
						product.link = currentHost() + '/work/' + product.slug + '/' + product.product_id;
					});
					vm.devisers = cartService.parseDevisersFromProducts(vm.cart);
				}, function(err) {
					//log err
					console.log(err);
				});
			}
		}
	}

	angular.module('todevise')
		.controller('checkoutCtrl', controller);

}());