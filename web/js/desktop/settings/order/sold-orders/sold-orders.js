(function () {
	"use strict";

	function controller(UtilService, orderDataService,cartService,$uibModal) {
		var vm = this;
		vm.markPackAware=markPackAware;
		vm.markPackShipped=markPackShipped;
		vm.editShippingData=editShippingData;
		vm.has_error = UtilService.has_error;
		vm.parseDate=UtilService.parseDate;
		vm.ordersTotalPrice=0;

		init();

		function init() {
			vm.orders.forEach(function(order) {
				order.totalPrice = order.totalShippingPrice = order.commission = order.to_receive = 0;
				order.packs.forEach(function(pack) {
					order.to_receive += (pack.pack_price + pack.shipping_price) * (1 - pack.pack_percentage_fee);
					order.commission += (pack.pack_price+pack.shipping_price)*pack.pack_percentage_fee;
					order.totalShippingPrice += pack.shipping_price;
					order.totalPrice += pack.pack_price + pack.shipping_price;
				});
				vm.ordersTotalPrice += order.totalPrice;
				cartService.parseTags(order, vm.tags);
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
			function onChangeStateSuccess(data) {
				if (!pack.editInfo) {
					order.packs.splice(order.packs.indexOf(pack),1);
					if (order.packs.length<1) {
						vm.orders.splice(vm.orders.indexOf(order),1);
						openInfoModal(order.id);
					}
					else {
						vm.orders[vm.orders.indexOf(order)].packs=data.packs;
					}
				}
				else {
					vm.orders[vm.orders.indexOf(order)].packs=data.packs;
				}
			}
			if (!pack.editInfo) {
				var modalInstance = $uibModal.open({
					component: 'modalAceptReject',
					resolve: {
						text: function () {
							return 'settings.orders.CONTINUE_MARK_SHIPPED';
						}
					}
				});
				modalInstance.result.then(function(data) {
					if (data) {
						pack.loading=true;
						ValidateUrl()
						orderDataService.changePackState({ company:vm.shippingCompany, eta: vm.eta, tracking_number:vm.trackingNumber, tracking_link:vm.trackLink }, {personId:pack.deviser_id,packId:pack.short_id, newState:'shipped' },onChangeStateSuccess, UtilService.onError)
					}
				}, function(err) {
					UtilService.onError(err);
				});
			}
			else {
				pack.loading=true;
				ValidateUrl()
				orderDataService.changePackState({ company:vm.shippingCompany, eta: vm.eta, tracking_number:vm.trackingNumber, tracking_link:vm.trackLink }, {personId:pack.deviser_id,packId:pack.short_id, newState:'shipped' },onChangeStateSuccess, UtilService.onError)
			}
		}

		function editShippingData(pack) {
			vm.trackLink=pack.shipping_info.tracking_link;
			vm.shippingCompany=pack.shipping_info.company;
			vm.trackingNumber=pack.shipping_info.tracking_number;
			vm.eta=pack.shipping_info.eta;
			pack.pack_state='aware';
			pack.editInfo=true;
		}

		function ValidateUrl() { 
			if(vm.trackLink && !/^(https?):\/\//i.test(vm.trackLink) && 'http://'.indexOf(vm.trackLink) !== 0 && 'https://'.indexOf(vm.trackLink) !== 0 ) {
				vm.trackLink= 'http://' + vm.trackLink;
			}
			else {
				return vm.trackLink;
			}
		}

		function openInfoModal(ordernumber) {
			var modalInstance = $uibModal.open({
				component: 'modalInfo',
				resolve: {
					text: function () {
						return 'settings.orders.ORDER_MOVED_TO_PAST';
					},
					translationData: function () {
						return ordernumber;
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
		templateUrl: currentHost() + '/js/desktop/settings/order/sold-orders/sold-orders.html',
		controller: controller,
		controllerAs: 'soldOrdersCtrl',
		bindings: {
			orders: '<',
			tags: '<'
		}
	}

	angular
	.module('settings')
	.component('soldOrders', component);
}());