(function () {
	"use strict";

	function controller(cartDataService, UtilService, cartService, cartEvents, $rootScope, localStorageUtilService, locationDataService, personDataService) {
		var vm = this;
		vm.deleteItem = deleteItem;
		vm.addQuantity = addQuantity;
		vm.subQuantity = subQuantity;
		vm.isObject = UtilService.isObject;
		var cart_id = localStorageUtilService.getLocalStorage('cart_id');
		vm.isDeviserOutsideEU = isDeviserOutsideEU;

		init();

		function init() {
			getEUCountries();
		}

		function addQuantity(product) {
			if((product.quantity < product.product_info.stock) || (product.product_info.stock === null))
				addProduct(product, 1);
		}

		function subQuantity(product) {
			if(product.quantity === 1)
				deleteItem(product.price_stock_id);
			else {
				addProduct(product, -1)
			}
		}

		function addProduct(product, quantity) {
			function onAddProductSuccess(data) {
				vm.cart = angular.copy(data);
				cartService.parseTags(vm.cart, vm.tags);
				$rootScope.$broadcast(cartEvents.cartUpdated, {cart: vm.cart});
			}

			cartDataService.addProduct({
				product_id: product.product_id,
				price_stock_id: product.price_stock_id,
				quantity: quantity
			}, {
				id: cart_id
			}, onAddProductSuccess, UtilService.onError);
		}

		function deleteItem(price_stock_id) {
			function onDeleteItemSuccess(data) {
				vm.cart = angular.copy(data);
				cartService.parseTags(vm.cart, vm.tags);
				$rootScope.$broadcast(cartEvents.cartUpdated, {cart: vm.cart});
			}

			cartDataService.deleteItem({
				id: cart_id,
				productId: price_stock_id,
			}, onDeleteItemSuccess, UtilService.onError);
		}

		function getEUCountries() {
			function onGetEUCountriesSuccess(data) {
				if (data) {
					vm.EUCountries=data.map(c => c.id);
				}
			}
			locationDataService.getEUCountries(null, onGetEUCountriesSuccess, UtilService.onError);
		}

		function isDeviserOutsideEU(pack) {
			if (!pack.updatingInfo) {
				pack.updatingInfo = true;
				if (!angular.isUndefined(pack.isOutsideEU)) {
					return pack.isOutsideEU;
				}
				function onGetPeopleSuccess(data) {
					if (data && vm.EUCountries) {
						pack.updatingInfo = false;
						pack.isOutsideEU= vm.EUCountries.indexOf(data.country)==-1;
						return pack.isOutsideEU;
					}
					return true;
				}
				personDataService.getPeople({personId: pack.deviser_id}, onGetPeopleSuccess, UtilService.onError);
			}
			else {
				return pack.isOutsideEU;
			}
		}
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/cart/shopping-cart/shopping-cart.html',
		controller: controller,
		controllerAs: 'shoppingCartCtrl',
		bindings: {
			state: '<',
			cart: '<',
			devisers: '<',
			tags: '<'
		}
	}

	angular
		.module('cart')
		.component('shoppingCart', component);

}());