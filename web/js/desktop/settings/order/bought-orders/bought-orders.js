(function () {
	"use strict";

	function controller(UtilService, cartService,$uibModal) {
		var vm = this;
		vm.parseDate=UtilService.parseDate;
		vm.ordersTotalPrice=0;
		vm.openTrackModal=openTrackModal;

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

		function openTrackModal(pack) {
				var modalInstance = $uibModal.open({
				component: 'modalInfo',
				resolve: {
					title: function () {
						return 'Track package';
					},
					text: function () {
						var bodyText='<span class="col-md-12" style="color: #9D9D9D;">SHIPPING DATE</span><label class="col-md-12">'+ pack.shipping_info.company +
						 '</label><span class="col-md-12" style="color: #9D9D9D;">TRACKING NUMBER</span><label class="col-md-12">'+ pack.shipping_info.tracking_number + '</label>';
						if (!angular.isUndefined(pack.shipping_info.tracking_link)) {
							bodyText=bodyText + '<span class="col-md-12" style="color: #9D9D9D;">TRACKING LINK</span><a class="col-md-12 red-text" ng-href="'+pack.shipping_info.tracking_link+'" target="blank">'+pack.shipping_info.tracking_link+'</a>';
						}
						return bodyText;
					},
					showButton: function () {
						return false;
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
			orders: '<'
		}
	}

	angular
	.module('settings')
	.component('boughtOrders', component);
}());