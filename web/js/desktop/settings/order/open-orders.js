(function () {
	"use strict";

	function controller(UtilService, orderDataService) {
		var vm = this;

		init();

		function init() {
			getOrders();
		}

		function getOrders() {
			function onGetOrdersSuccess(data) {
				if(angular.isArray(data.items) && data.items.length > 0)
					vm.orders = angular.copy(data.items);
			}
			//ToDo: get deviser orders
			orderDataService.getOrder(null, onGetOrdersSuccess, UtilService.onError);
		}
	}

	angular
		.module('settings')
		.controller('openOrdersCtrl', controller);
}());