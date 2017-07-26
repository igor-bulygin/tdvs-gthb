(function () {
	"use strict";

	function controller(UtilService, orderDataService) {
		var vm = this;
		vm.changeOrderState=changeOrderState;
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
			orderDataService.getDeviserOrders({pack_state:"open", personId:vm.deviserId}, onGetOrdersSuccess, UtilService.onError);
		}

		function changeOrderState(order) {
			if (order.state==='aware') {
				order.state='preparing'
			}
			else if (order.state==='preparing') {
				order.state='shipped';
				//TODO send changed state
				vm.orders.splice(vm.orders.indexOf(order),1);
			}
			
		}
	}

	angular
	.module('settings')
	.controller('openOrdersCtrl', controller);
}());