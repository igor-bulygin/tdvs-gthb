(function () {
	"use strict";

	function controller(orderDataService, cartService, $location) {
		var vm = this;
		vm.state = {
			state: 4
		};

		init();

		function init() {
			getOrder();
		}

		function onError(err) {
			console.log(err);
		}

		function getOrder() {
			function onGetOrderSuccess(data) {
				vm.order = angular.copy(orderData);
				cartService.parseTags(vm.order);
				vm.devisers = cartService.parseDevisersFromProducts(vm.order);
			}
			var url = $location.absUrl();
			var order_id = url.split('/')[url.split('/').length-1];

			orderDataService.getOrder({id:order_id}, onGetOrderSuccess, onError);
		}
	}
 
 	angular.module('todevise')
 		.controller('orderSuccessCtrl', controller);

}());