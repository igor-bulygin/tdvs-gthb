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
			cartDataService.Cart.save()
				.$promise.then(function (cartData) {
					var cart_id = cartData.id;
					UtilService.setLocalStorage('cart_id', cart_id);
				}, function (err) {
					//TODO: show err;
				})
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
						product.link = currentHost() + '/work/' + product.product_slug + '/' + product.product_id;
					});
					vm.devisers = cartService.parseDevisersFromProducts(vm.cart);
				}, function(err) {
					createCart();
					//TODO: show err
					console.log(err);
				});
			} else {
				createCart();
			}
		}
	}

	angular.module('todevise')
		.controller('checkoutCtrl', controller);

}());