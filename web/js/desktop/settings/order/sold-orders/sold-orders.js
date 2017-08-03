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
			angular.forEach(vm.orders, function(order, key) {
				order.totalPrice = 0;
				order.commission=0;
				order.totalShippingPrice=0;
				angular.forEach(order.packs, function(pack, keyPack) {
					order.totalPrice = order.totalPrice + pack.pack_price;
					order.totalShippingPrice = order.totalShippingPrice + pack.shipping_price;
					order.commission= order.commission + ((pack.pack_price*pack.pack_percentage_fee)/100);
				});
				order.total= order.totalPrice + order.totalShippingPrice + order.commission;
				vm.ordersTotalPrice=vm.ordersTotalPrice + order.totalPrice;
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
							return '<p>You will mark this order as shipped.</p><p><strong>This action can not be undone.</strong></p><p>Do you wish to continue?</p>';
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
						return 'Order ' + ordernumber+ ' was moved to Past orders. For payment information see Billing & Payments';
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
			orders: '<'
		}
	}

	angular
	.module('settings')
	.component('soldOrders', component);
}());