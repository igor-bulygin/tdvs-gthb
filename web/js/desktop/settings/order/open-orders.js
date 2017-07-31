(function () {
	"use strict";

	function controller(UtilService, orderDataService) {
		var vm = this;
		vm.deviserId=person.id;		
		vm.orders=[];
		vm.enabledStates=[{value:"open", name : "Open orders"},{value:"past", name :"Past orders"},{value:"", name : "All orders"}];
		vm.stateFilter=vm.enabledStates[0];
		vm.enabledTypes=[];
		vm.isDeviser=false;
		if (person.type[0]==2) {
			vm.isDeviser=true;
			vm.enabledTypes.push({value:"received", name : "Works you sold"});
		}
		vm.enabledTypes.push({value:"done", name : "Works you bought"});
		vm.typeFilter=vm.enabledTypes[0];
		vm.getOrders=getOrders;
		
		init();

		function init() {
			getOrders();
		}

		function getOrders() {
			vm.loading=true;
			function onGetOrdersSuccess(data) {
				if(angular.isArray(data.items) && data.items.length > 0) {
					vm.orders = angular.copy(data.items); 
				}				
				vm.loading=false;
			}
			switch (vm.typeFilter.value) {
				case "done":
					orderDataService.getOrder({pack_state:vm.stateFilter.value, personId:vm.deviserId}, onGetOrdersSuccess, UtilService.onError);
					break;
				case "received":
					orderDataService.getDeviserPack({pack_state:vm.stateFilter.value, personId:vm.deviserId}, onGetOrdersSuccess, UtilService.onError);
					break;
			}
			
		}
	}

	angular
	.module('settings')
	.controller('ordersCtrl', controller);
}());