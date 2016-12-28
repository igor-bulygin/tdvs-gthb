(function () {
	"use strict";

	function controller(cartDataService, UtilService) {
		var vm = this;

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
				}, function(err) {
					//log err
				})
			}
		}

		init();
	}

	angular.module('todevise')
		.controller('checkoutCtrl', controller);

}());