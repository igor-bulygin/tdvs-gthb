(function () {
	"use strict";

	function controller(cartDataService, tagDataService, productDataService, UtilService, cartService) {
		var vm = this;

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
					vm.devisers = cartService.parseDevisersFromProducts(vm.cart);
				}, function(err) {
					//log err
				});
			}
		}
	}

	angular.module('todevise')
		.controller('checkoutCtrl', controller);

}());