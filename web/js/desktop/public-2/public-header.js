(function () {
	"use strict";



	function config($translatePartialLoaderProvider) {
		$translatePartialLoaderProvider.addPart('header');
	}

	function controller(personDataService, $window, UtilService, localStorageUtilService, cartDataService, cartService,$scope, cartEvents) {
		var vm = this;
		vm.logout = logout;
		vm.cartQuantity=0;
		init();

		function init() {
			getCartQuantity();
		}

		function logout(){
			function onLogoutSuccess(data) {
				localStorageUtilService.removeLocalStorage('access_token');
				localStorageUtilService.removeLocalStorage('cart_id');
				$window.location.href = currentHost();
			}

			personDataService.logout(vm.session_logout, null, onLogoutSuccess, UtilService.onError);
		}

		function updateCart(cart){
			vm.cart = angular.copy(cart);
			cartService.setTotalItems(vm.cart);
			vm.cartQuantity=vm.cart.totalItems;
		}

		function getCartQuantity() {
			var cart_id = localStorageUtilService.getLocalStorage('cart_id');
			function onGetCartSuccess(data) {
				updateCart(data);
			}
			function onGetCartError(err) {
				UtilService.onError(err);
			}
			if(cart_id) {
				cartDataService.getCart({id: cart_id}, onGetCartSuccess, onGetCartError);
			}
		}

		$scope.$on(cartEvents.cartUpdated, function(evt,data){ 
			updateCart(data.cart);
		}, true);
	}

	angular.module('header', ['api', 'util', 'box', 'pascalprecht.translate'])
		.config(config)
		.controller('publicHeaderCtrl', controller);
}());