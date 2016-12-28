(function () {
	"use strict";

	function controller(cartDataService, tagDataService, productDataService, UtilService) {
		var vm = this;
		vm.devisers = [];

		init();

		function init() {
			getCart();
			getTags();
		}

		function getCart() {
			var cart_id = UtilService.getLocalStorage('cart_id');
			if(cart_id) {
				cartDataService.Cart.get({
					id: cart_id
				}).$promise.then(function (cartData) {
					vm.cart = angular.copy(cartData);
					vm.cart.products.forEach(function(product) {
						if(vm.devisers.indexOf(product.deviser_id) < 0) {
							vm.devisers.push(product.deviser_id);
						}
					});
				}, function(err) {
					//log err
				});
			}
		}

		function getTags() {
			tagDataService.Tags.get()
				.$promise.then(function(dataTags) {
					vm.tags = angular.copy(dataTags.items);
				}, function(err) {
					//error
				});
		}

	}

	angular.module('todevise')
		.controller('checkoutCtrl', controller);

}());