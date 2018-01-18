(function () {
	"use strict";

	function controller(locationDataService, UtilService) {
		var vm = this;
		vm.deleteShippingExpressTime = deleteShippingExpressTime;
		vm.deleteFreeShippingFrom = deleteFreeShippingFrom;
		vm.has_error = UtilService.has_error;

		init();

		function init() {
			vm.show_express_shipping = !!vm.setting.shipping_express_time;
			vm.show_free_shipping = !!vm.setting.free_shipping_from;
		}

		function deleteShippingExpressTime() {
			delete vm.setting.shipping_express_time;
			vm.setting.prices = vm.setting.prices.map(function(element) {
				if(element.price_express)
					delete element.price_express;
				return element;
			});
		}

		function deleteFreeShippingFrom() {
			delete vm.setting.free_shipping_from;
		}

	}

	var component = {
		templateUrl: currentHost() + "/js/desktop/settings/shipping/types/types.html",
		controller: controller,
		controllerAs: "shippingTypesCtrl",
		bindings: {
			setting: '<',
			currency: '<'
		}
	}

	angular
		.module('settings')
		.component('shippingTypes', component);

}());