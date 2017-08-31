(function () {
	"use strict";

	function controller(UtilService, orderDataService, $translate) {
		var vm = this;
		vm.deviserId=person.id;		
		vm.orders=[];
		vm.enabledStates=[{value:"open", name : "settings.orders.OPEN"},{value:"past", name :"settings.orders.PAST"},{value:"", name : "settings.orders.ALL"}];
		vm.stateFilter=vm.enabledStates[0].value;
		vm.enabledTypes=[];
		vm.isDeviser=false;
		if (person.type[0]==2) {
			vm.isDeviser=true;
			vm.enabledTypes.push({value:"received", name : "settings.orders.SALES"});
		}
		vm.enabledTypes.push({value:"done", name : "settings.orders.MY_PURCHASE"});
		vm.typeFilter=vm.enabledTypes[0];
		vm.getOrders=getOrders;

		init();

		function init() {
			getOrders();
		}

		function getOrders() {
			vm.loading=true;
			function onGetOrdersSuccess(data) {
				vm.orders = angular.copy(data.items); 
				vm.loading=false;
			}
			switch (vm.typeFilter.value) {
				case "done":
					orderDataService.getOrder({pack_state:vm.stateFilter, personId:vm.deviserId}, onGetOrdersSuccess, UtilService.onError);
					break;
				case "received":
					orderDataService.getDeviserPack({pack_state:vm.stateFilter, personId:vm.deviserId}, onGetOrdersSuccess, UtilService.onError);
					break;
			}
			
		}
	}

	angular
	.module('settings')
	.controller('ordersCtrl', controller);
}());