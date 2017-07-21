(function () {
	"use strict";

	function controller(UtilService, orderDataService) {
		var vm = this;
		vm.changeDeviserState=changeDeviserState;
		vm.deviser={state:'aware'};
		vm.radioModel = vm.deviser.state;

		init();

		function init() {
			//getOrders();
			vm.orders= [{id: 1, date: "20/7/17", subtotal: 120, person_info: {first_name: "buyerTest",last_name: "buyT",address:"street 1" , city: "London", country: "England"}},
			{id:2, date: "20/7/17", subtotal: 520, person_info: {first_name: "buyerTest2",last_name: "buyT2",address:"street 1" , city: "London", country: "England"}}];
		}

		function getOrders() {
			function onGetOrdersSuccess(data) {
				if(angular.isArray(data.items) && data.items.length > 0)
					vm.orders = angular.copy(data.items);
			}
			//ToDo: get deviser orders
			orderDataService.getOrders(null, onGetOrdersSuccess, UtilService.onError);
		}

		function changeDeviserState() {
			vm.radioModel = vm.deviser.state;
		}
	}

	angular
		.module('settings')
		.controller('openOrdersCtrl', controller);
}());