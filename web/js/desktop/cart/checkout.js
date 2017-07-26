(function () {
	"use strict";

	function controller(UtilService, cartDataService, localStorageUtilService, cartService, locationDataService) {
		var vm = this;
		vm.person = person;
		vm.checkout_state = 1;

		init();

		function init() {
			getCountries();
			getCart();
		}

		function getCountries() {
			function onGetCountriesSuccess(data) {
				vm.countries = angular.copy(data.items);
			}

			locationDataService.getCountry(null, onGetCountriesSuccess, UtilService.onError);
		}

		function getCart() {
			var cart_id = localStorageUtilService.getLocalStorage('cart_id');

			function onGetCartSuccess(data) {
				vm.cart = angular.copy(data);
				cartService.parseTags(vm.cart);
				cartService.setTotalItems(vm.cart),
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