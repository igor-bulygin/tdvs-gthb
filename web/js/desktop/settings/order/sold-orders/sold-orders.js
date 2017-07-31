(function () {
	"use strict";

	function controller(UtilService, orderDataService,cartService) {
		var vm = this;
		vm.markPackAware=markPackAware;
		vm.markPackShipped=markPackShipped;
		vm.editShippingData=editShippingData;
		vm.has_error = UtilService.has_error;
		init();

		function init() {
			angular.forEach(vm.orders, function(order, key) {
				order.totalPrice = 0;
				order.commission=0;
				order.totalShippingPrice=0;
				order.order_date= new Date(order.order_date.sec*1000);
				angular.forEach(order.packs, function(pack, keyPack) {
					order.totalPrice = order.totalPrice + pack.pack_price;
					order.totalShippingPrice = order.totalShippingPrice + pack.shipping_price;
					order.commission= order.commission + ((pack.pack_price*pack.pack_percentage_fee)/100);
				});
				order.total= order.totalPrice + order.totalShippingPrice + order.commission;
cartService.parseTags(order);
			});
		}

		function markPackAware(order,pack) {
			pack.loading=true;
			function onChangeStateSuccess(data) {
				vm.orders[vm.orders.indexOf(order)].packs=data.packs;
			}
			orderDataService.changePackState({}, {personId:pack.deviser_id,packId:pack.short_id, newState:'aware' },onChangeStateSuccess, UtilService.onError);
		}

		function markPackShipped(order,pack) {
			pack.loading=true;
			function onChangeStateSuccess(data) {
				if (!pack.editInfo) {
					order.packs.splice(order.packs.indexOf(pack),1);
					if (order.packs.length<1) {
						vm.orders.splice(vm.orders.indexOf(order),1);
					}
					else {
						vm.orders[vm.orders.indexOf(order)].packs=data.packs;
					}
				}
				else {
					vm.orders[vm.orders.indexOf(order)].packs=data.packs;
				}
			}
			orderDataService.changePackState({ company:vm.shippingCompany, eta: vm.eta, tracking_number:vm.trackingNumber, tracking_link:vm.trackLink }, {personId:pack.deviser_id,packId:pack.short_id, newState:'shipped' },onChangeStateSuccess, UtilService.onError)
		}

		function editShippingData(pack) {
			vm.trackLink=pack.shipping_info.tracking_link;
			vm.shippingCompany=pack.shipping_info.company;
			vm.trackingNumber=pack.shipping_info.tracking_number;
			vm.eta=pack.shipping_info.eta;
			pack.pack_state='aware';
			pack.editInfo=true;
		}
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/settings/order/sold-orders/sold-orders.html',
		controller: controller,
		controllerAs: 'soldOrdersCtrl',
		bindings: {
			orders: '<'
		}
	}

	angular
	.module('settings')
	.component('soldOrders', component);
}());