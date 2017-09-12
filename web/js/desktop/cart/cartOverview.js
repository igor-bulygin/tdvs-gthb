(function () {
	"use strict";

	function controller(cartDataService, tagDataService, productDataService, UtilService, cartService, localStorageUtilService) {
		var vm = this;

		init();

		function init() {
			getTags();
		}

		function getTags() {
			function onGetTagsSuccess(data) {
				vm.tags = angular.copy(data);
				getCart();
			}

			tagDataService.getTags(null, onGetTagsSuccess, UtilService.onError);
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
				cartService.parseTags(vm.cart, vm.tags);
				cartService.setTotalItems(vm.cart);
				cartService.setProductsAmount(vm.cart);
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

	angular.module('cart')
		.controller('cartOverviewCtrl', controller);

}());