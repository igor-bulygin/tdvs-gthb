(function () {
	"use strict";

	function controller(UtilService, orderDataService) {
		var vm = this;
		vm.changePackState=changePackState;
		vm.deviserId=person.id;
		vm.orders=[];
		init();

		function init() {
			getOrders();
			
		}

		function getOrders() {
			function onGetOrdersSuccess(data) {
				if(angular.isArray(data.items) && data.items.length > 0) {
					vm.orders = angular.copy(data.items); 
				}
				angular.forEach(vm.orders, function(order, key) {
					order.state='aware';
					order.totalPrice = 0;
					order.commission=0;
					order.totalShippingPrice=0;
					order.order_date= new Date(order.order_date.sec*1000)
					angular.forEach(order.packs, function(pack, keyPack) {
						order.totalPrice = order.totalPrice + pack.pack_price;
						order.totalShippingPrice = order.totalShippingPrice + pack.shipping_price;
						order.commision= order.commision + pack.pack_percentage_fee;
					});
					order.total= order.totalPrice + order.totalShippingPrice + order.commission;
				});
			}
			orderDataService.getDeviserPack({pack_state:"open", personId:vm.deviserId}, onGetOrdersSuccess, UtilService.onError);
		}

		function changePackState(order,pack) {
			if (pack.pack_state==='aware') {
				pack.pack_state='preparing'
			}
			else if (pack.pack_state==='preparing') {
				pack.pack_state='shipped';
				//TODO send changed state
				order.packs.splice(order.packs.indexOf(pack),1);
				if (order.packs.length<1) {
					vm.orders.splice(vm.orders.indexOf(order),1);
				}
			}
			
		}
	}

	angular
	.module('settings')
	.controller('openOrdersCtrl', controller);
}());