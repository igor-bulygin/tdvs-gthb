(function () {
	"use strict";

	function config($translatePartialLoaderProvider) {
		$translatePartialLoaderProvider.addPart('header');
	}

	function controller(personDataService, $window, UtilService, localStorageUtilService, cartDataService, cartService,$scope, cartEvents, chatDataService, $interval) {
		var vm = this;
		vm.logout = logout;
		vm.cartQuantity=0;
		vm.msgQuantity=0;
		vm.openMenu=false;
		vm.selectedCategory = _selectedCategoryId;
		vm.selectedSearchTypeId = _searchTypeId; // taken from global @var _searchTypeId defined in /components/views/PublicHeader2/PublicHeader2.php  (from GET request)
        vm.searchTypes = UtilService.getSearchTypes(); // taken from UtilService to get search types in select in search block
		init();

		function init() {
			getMsgQuantity();
			$interval(function() {
				getMsgQuantity();
			}, 60000);
			getCartQuantity();
		}

		function logout(){
			function onLogoutSuccess(data) {
				localStorageUtilService.removeLocalStorage('access_token');
				localStorageUtilService.removeLocalStorage('cart_id');
				localStorageUtilService.removeLocalStorage('sesion_id');
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

		function getMsgQuantity() {
			vm.msgQuantity = 0;
			function onGetChatsSuccess(data) {
				angular.forEach(data.items, function(chat){
					if (chat.preview.unread) {
						vm.msgQuantity++;
					}
				}); 
			}
			chatDataService.getChats({}, onGetChatsSuccess, UtilService.onError);
		}

		$scope.$on(cartEvents.cartUpdated, function(evt,data){ 
			updateCart(data.cart);
		}, true);
	}

	angular.module('header', ['api', 'util', 'box', 'pascalprecht.translate'])
		.config(config)
		.controller('publicHeaderCtrl', controller);
}());