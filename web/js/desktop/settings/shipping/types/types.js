(function () {
	"use strict";

	function controller(locationDataService, UtilService) {
		var vm = this;
		vm.deleteShippingExpressTime = deleteShippingExpressTime;
		vm.has_error = UtilService.has_error;

		init();

		function init() {
			if(vm.setting.shipping_express_time)
				vm.show_express_shipping = true;
		}

		function deleteShippingExpressTime() {
			delete vm.setting.shipping_express_time;
			vm.setting.prices = vm.setting.prices.map(function(element) {
				if(element.price_express)
					delete element.price_express;
				return element;
			});
		}

	}

	var component = {
		templateUrl: currentHost() + "/js/desktop/settings/shipping/types/types.html",
		controller: controller,
		controllerAs: "shippingTypesCtrl",
		bindings: {
			setting: '<'
		}
	}

	angular
		.module('settings')
		.component('shippingTypes', component);

}());