(function () {
	"use strict";

	function controller(UtilService, orderDataService, $translate, tagDataService) {
		var vm = this;
		vm.deviserId=person.id;
		vm.getOrders=getOrders;
		vm.loading=true;
		vm.orderOptions = [
			{name:"settings.orders.ORDER_OPEN",value:"open"},
			{name:"settings.orders.ORDER_PAST",value:"past"},
			{name:"settings.orders.ORDER_DATE_DESC",value:"date_desc"},
			{name:"settings.orders.ORDER_DATE_ASC",value:"date_asc"},
		];
		vm.orderFilter = {name:"settings.orders.ORDER_OPEN_FIRST",value:"open"};

		init();

		function init() {
			vm.isDeviser=false;
			var open_orders_translation;
			vm.orders=[];
			vm.enabledTypes=[];
			if (person.type.indexOf(2)>-1) {
				vm.isDeviser=true;
				vm.enabledTypes.push({value:"received", name: "settings.orders.SALES"});
				open_orders_translation = 'settings.orders.TO_SEND';
			} else {
				open_orders_translation = 'settings.orders.OPEN';
			}
			vm.enabledTypes.push({value:"done", name: "settings.orders.MY_PURCHASE"});
			vm.typeFilter=vm.enabledTypes[0];
			vm.enabledStates=[
				{
					value: "",
					name: "settings.orders.ALL"
				},
				{
					value:"open",
					name: open_orders_translation
				}];
			vm.stateFilter=vm.enabledStates[0].value;
			getTags();
		}

		function getTags() {
			function onGetTagsSuccess(data) {
				vm.tags = angular.copy(data);
				getOrders();
			}

			tagDataService.getTags(null, onGetTagsSuccess, UtilService.onError);
		}

		function getOrders() {
			vm.loading=true;
			function onGetOrdersSuccess(data) {
				vm.orders = angular.copy(data.items); 
				// vm.orderOptions =[];
				// vm.orders.forEach(function(order) {
					// order.packs.forEach(function(pack) {
					// 	if (vm.orderOptions.map(function(e) { return e.value; }).indexOf(pack.pack_state) === -1) {
					// 		vm.orderOptions.push({ name:"settings.orders." + pack.pack_state.toUpperCase(), value:pack.pack_state});
					// 	}
					// });
				// });
				// if (vm.orderOptions.length>0) {
				// 	vm.orderFilter = vm.orderOptions[0];
				// }
				vm.loading=false;
			}
			switch (vm.typeFilter.value) {
				case "done":
					orderDataService.getOrder({
						pack_state: vm.stateFilter,
						personId: vm.deviserId,
						order_type: vm.orderFilter.value
					}, onGetOrdersSuccess, UtilService.onError);
					break;
				case "received":
					orderDataService.getDeviserPack(
						{pack_state:vm.stateFilter,
						 personId:vm.deviserId,
						 order_type: vm.orderFilter.value
						}, onGetOrdersSuccess, UtilService.onError);
					break;
			}
			
		}
	}

	angular
	.module('settings')
	.controller('ordersCtrl', controller);
}());