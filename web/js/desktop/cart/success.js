(function () {
	"use strict";

	function controller(orderDataService, cartService, $location) {
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
            orderDataService.Order.get({
				id: order_id
			}).$promise.then(function (orderData) {
				vm.order = angular.copy(orderData);
				cartService.parseTags(vm.order);
				vm.devisers = cartService.parseDevisersFromProducts(vm.order);
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