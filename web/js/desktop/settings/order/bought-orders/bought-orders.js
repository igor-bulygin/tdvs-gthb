(function () {
	"use strict";

	function controller(UtilService, cartService,$uibModal) {
		var vm = this;
		vm.parseDate=UtilService.parseDate;
		vm.ordersTotalPrice=0;
		vm.openTrackModal=openTrackModal;
		vm.parseDate=UtilService.parseDate;

		init();

		function init() {
			vm.orders.forEach(function(order) {
				order.total = 0;
				order.packs.forEach(function(pack) {
					order.total += pack.pack_price + pack.shipping_price;
				})
				vm.ordersTotalPrice=vm.ordersTotalPrice + order.total;
				cartService.parseTags(order, vm.tags);
			})
		}

		function openTrackModal(pack) {
				var modalInstance = $uibModal.open({
				component: 'modalInfo',
				resolve: {
					title: function () {
						return 'Track package';
					},
					text: function () {
						if (!angular.isUndefined(pack.shipping_info.tracking_link) && pack.shipping_info.tracking_link != null ) {
							return "settings.orders.SHIPPING_DATA_WITH_LINK";
						}
						return "settings.orders.SHIPPING_DATA";
					},
					showButton: function () {
						return false;
					},
					translationData: function () {
						return pack.shipping_info.company;
					},
					translationData2: function () {
						return pack.shipping_info.tracking_number;
					},
					translationData3: function () {
						if (!angular.isUndefined(pack.shipping_info.tracking_link) && pack.shipping_info.tracking_link != null ) {
							return pack.shipping_info.tracking_link;
						}
						else {
							return "";
						}
					}
				}
			});
			modalInstance.result.then(function(data) {
				return data;
			}, function(err) {
				UtilService.onError(err);
			});
		} 
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/settings/order/bought-orders/bought-orders.html',
		controller: controller,
		controllerAs: 'boughtOrdersCtrl',
		bindings: {
			orders: '<',
			tags: '<'
		}
	}

	angular
	.module('settings')
	.component('boughtOrders', component);
}());