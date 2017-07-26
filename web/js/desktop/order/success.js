(function () {
	"use strict";

	function controller(orderDataService, cartService, $location, UtilService) {
		var vm = this;
		vm.person = person;
		vm.order_id = order_id;
		vm.isObject = angular.isObject;

		init();

		function init() {
			getOrder();
		}

		function getOrder() {
			function onGetOrderSuccess(orderData) {
				vm.order = angular.copy(orderData);
				cartService.parseTags(vm.order);
			}

			orderDataService.getOrder({
				personId: vm.person.id,
				orderId: vm.order_id
			}, onGetOrderSuccess, UtilService.onError);
		}
	}
 
 	angular.module('todevise')
 		.controller('orderSuccessCtrl', controller);

}());