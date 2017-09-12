(function () {
	"use strict";

	function controller(orderDataService, cartService, $location, UtilService, tagDataService) {
		var vm = this;
		vm.person = person;
		vm.order_id = order_id;
		vm.isObject = angular.isObject;

		init();

		function init() {
			getTags();
		}

		function getTags() {
			function onGetTagsSuccess(data) {
				vm.tags = angular.copy(data);
				getOrder();
			}

			tagDataService.getTags(null, onGetTagsSuccess, UtilService.onError);
		}

		function getOrder() {
			function onGetOrderSuccess(orderData) {
				vm.order = angular.copy(orderData);
				cartService.parseTags(vm.order, vm.tags);
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