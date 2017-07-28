(function () {
	"use strict";

	function controller(UtilService, orderDataService) {
		var vm = this;
		vm.markPackAware=markPackAware;
		vm.markPackShipped=markPackShipped;

		init();

		function init() {
			angular.forEach(vm.orders, function(order, key) {
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

		function markPackAware(order,pack) {
			function onChangeStateSuccess(data) {
				order=data;
			}
			orderDataService.changePackState({}, {personId:pack.deviser_id,packId:pack.short_id, newState:'aware' },onChangeStateSuccess, UtilService.onError)
		}

		function markPackShipped(order,pack) {
			function onChangeStateSuccess(data) {
				order=data;
				order.packs.splice(order.packs.indexOf(pack),1);
				if (order.packs.length<1) {
					vm.orders.splice(vm.orders.indexOf(order),1);
				}
			}
			orderDataService.changePackState({ company:vm.shippingCompany, eta: vm.eta, tracking_number:vm.trackingNumber, tracking_link:vm.trackLink }, {personId:pack.deviser_id,packId:pack.short_id, newState:'shipped' },onChangeStateSuccess, UtilService.onError)
		}
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/settings/order/sold-orders/sold-orders.html',
		controller: controller,
		controllerAs: 'soldOrdersCtrl',
		bindings: {
			orders: '='
		}
	}

	angular
	.module('settings')
	.component('soldOrders', component);
}());