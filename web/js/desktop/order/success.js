(function () {
	"use strict";

	function controller(orderDataService, cartService, $location, UtilService) {
		var vm = this;
		vm.person = person;
		vm.state = {
			state: 4
		};

		init();

		function init() {
			getOrder();
		}

		function getOrder() {
			function onGetOrderSuccess(orderData) {
				vm.order = angular.copy(orderData);
				cartService.parseTags(vm.order);
			}
			var url = $location.absUrl();
			var order_id = url.split('/')[url.split('/').length-1];

			orderDataService.getOrder({
				personId: vm.person.short_id,
				orderId:order_id
			}, onGetOrderSuccess, UtilService.onError);
		}
	}
 
 	angular.module('todevise')
 		.controller('orderSuccessCtrl', controller);

}());