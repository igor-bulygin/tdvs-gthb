(function () {
	"use strict";

	function controller(UtilService, cartService) {
		var vm = this;
		vm.parseDate=UtilService.parseDate;
		vm.ordersTotalPrice=0;

		init();

		function init() {
			angular.forEach(vm.orders, function(order, key) {
					order.totalPrice = 0;
					order.order_date= new Date(order.order_date.sec*1000);
					angular.forEach(order.packs, function(pack, keyPack) {
						order.totalPrice = order.totalPrice + pack.pack_price;
					});
					order.total= order.totalPrice + order.totalShippingPrice + order.commission;
					vm.ordersTotalPrice=vm.ordersTotalPrice + order.totalPrice;
					cartService.parseTags(order);
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