(function () {
	"use strict";

	function controller(UtilService, cartDataService, localStorageUtilService, cartService) {
		var vm = this;
		vm.person = person;

		init();

		function init() {
			getCart();
		}

		function getCart() {
			var cart_id = localStorageUtilService.getLocalStorage('cart_id');

			function onGetCartSuccess(data) {
				vm.cart = angular.copy(data);
				cartService.parseTags(vm.cart);
				vm.cart.shipping_address = Object.assign({}, vm.person.personal_info);
			}

			function onGetCartError(err) {
				UtilService.onError(err);
			}

			if(cart_id) {
				cartDataService.getCart({id: cart_id}, onGetCartSuccess, onGetCartError);
			}
		}
	}

	angular
		.module('cart')
		.controller('checkoutCtrl', controller);
}());