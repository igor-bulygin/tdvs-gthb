(function () {
	"use strict";

	function controller(UtilService, orderDataService) {
		var vm = this;
		vm.deviserId=person.id;		
		vm.orders=[];
		vm.enabledStates=[{value:"open", name : "Open"},{value:"past", name :"Past"},{value:" ", name : "All"}];
		vm.stateFilter=vm.enabledStates[0].value;
		vm.enabledTypes=[];
		vm.isDeviser=false;
		if (person.type[0]==2) {
			vm.isDeviser=true;
			vm.enabledTypes.push({value:"received", name : "Sales"});
		}
		vm.enabledTypes.push({value:"done", name : "My purchase"});
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