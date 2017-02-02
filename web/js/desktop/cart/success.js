(function () {
	"use strict";

	function controller(cartDataService, cartService, $location) {
		var vm = this;
		vm.state = {
			state: 4
		};

		function init() {
			getOrder();
		}

		function getOrder() {
			var url = $location.absUrl();
			var order_id = url.split('/')[url.split('/').length-1];
			cartDataService.Cart.get({
				id: order_id
			}).$promise.then(function (orderData) {
				vm.cart = angular.copy(orderData);
				cartService.parseTags(vm.cart);
				vm.devisers = cartService.parseDevisersFromProducts(vm.cart);
				console.log(orderData);
			}, function(err) {
				console.log(err);
			})
		}

		init();
	}

	angular.module('todevise')
		.controller('orderSuccessCtrl', controller);
}());