(function () {
	"use strict";

	function controller(UtilService, orderDataService) {
		var vm = this;

		init();

		function init() {
			angular.forEach(vm.orders, function(order, key) {
					order.totalPrice = 0;
					order.order_date= new Date(order.order_date.sec*1000)
					angular.forEach(order.packs, function(pack, keyPack) {
						order.totalPrice = order.totalPrice + pack.pack_price;
					});
					order.total= order.totalPrice + order.totalShippingPrice + order.commission;
				});
		}
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/settings/order/bought-orders/bought-orders.html',
		controller: controller,
		controllerAs: 'boughtOrdersCtrl',
		bindings: {
			orders: '<'
		}
	}

	angular
	.module('settings')
	.component('boughtOrders', component);
}());