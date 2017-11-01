(function () {
	"use strict";

	function controller(UtilService, orderDataService,cartService,$uibModal, uploadDataService, $location) {
		var vm = this;
		vm.markPackAware=markPackAware;
		vm.markPackShipped=markPackShipped;
		vm.editShippingData=editShippingData;
		vm.has_error = UtilService.has_error;
		vm.parseDate=UtilService.parseDate;
		vm.uploadInvoice = uploadInvoice;
		vm.ordersTotalPrice=0;

		init();

		function init() {
			vm.orders.forEach(function(order) {
				order.totalPrice = order.totalShippingPrice = order.commission = order.to_receive = 0;
				order.packs.forEach(function(pack) {
					order.to_receive += pack.pack_total_price - pack.pack_total_fee;
					order.commission += pack.pack_total_fee;
					order.totalShippingPrice += pack.shipping_price;
					order.totalPrice += pack.pack_total_price;
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
				vm.orders[vm.orders.indexOf(order)].packs=data.packs;
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
						validateUrl();
						orderDataService.changePackState({ company:vm.shippingCompany, eta: vm.eta, tracking_number:vm.trackingNumber, tracking_link:vm.trackLink, invoice_url:pack.invoice_url }, {personId:pack.deviser_id,packId:pack.short_id, newState:'shipped' },onChangeStateSuccess, UtilService.onError);
						vm.shippingCompany=null;
						vm.eta=null;
						vm.trackingNumber=null;
						vm.trackLink=null;
					}
				}, function(err) {
					UtilService.onError(err);
				});
			}
			else {
				pack.loading=true;
				validateUrl();
				orderDataService.changePackState({ company:vm.shippingCompany, eta: vm.eta, tracking_number:vm.trackingNumber, tracking_link:vm.trackLink, invoice_url:pack.invoice_url  }, {personId:pack.deviser_id,packId:pack.short_id, newState:'shipped' },onChangeStateSuccess, UtilService.onError)
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

		function validateUrl() { 
			if(vm.trackLink && !/^(https?):\/\//i.test(vm.trackLink) && 'http://'.indexOf(vm.trackLink) !== 0 && 'https://'.indexOf(vm.trackLink) !== 0 ) {
				vm.trackLink= 'http://' + vm.trackLink;
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

		function uploadInvoice(invoice, errInvoice,pack) {
			if (invoice && !pack.editInfo) {
				vm.actualPack=pack;
				function onUploadInvoiceSuccess(data, file, pack) {
						delete file.progress;
						pack.invoice_url=  data.data.filename;
						pack.invoice_link=  currentHost() + data.data.url;
						vm.invoice=invoice;
						vm.errFiles = errInvoice;
				}
				function onWhileUploadingInvoice(evt, file) {
					if (file) {
						file.progress = parseInt(100.0 * evt.loaded / evt.total);
					}
				}
				vm.invoice=invoice;
				vm.errFiles = errInvoice;
				//upload invoice
				var data = {
					person_id: person.short_id,
					pack_id: pack.short_id,
					type: 'person-pack-invoice',
					file: vm.invoice
				};
				uploadDataService.UploadFile(data,
					function(data) {
						return onUploadInvoiceSuccess(data, vm.invoice,pack);
					}, UtilService.onError,
					function(evt) {
						return onWhileUploadingInvoice(evt, vm.invoice);
					});
			}
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