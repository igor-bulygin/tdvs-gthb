(function () {
	"use strict";

	function controller(UtilService, locationDataService) {
		var vm = this;
		vm.addPrice = addPrice;
		vm.setUnlimitedWeight = setUnlimitedWeight;
		vm.has_error = UtilService.has_error;

		init();

		function init() {
			getCurrency();
			if(!angular.isArray(vm.setting.prices) || vm.setting.prices.length == 0) {
				vm.setting.prices = []
			}
		}

		function getCurrency() {
			function onGetCountrySuccess(data) {
				vm.currency = angular.copy(data.currency_code);
			}


			locationDataService.getCountry({
				countryCode: vm.setting.country_code
			}, onGetCountrySuccess, UtilService.onError)
		}

		function addPrice() {
			var weight = vm.setting.prices.length > 0 ? vm.setting.prices[vm.setting.prices.length - 1]['max_weight'] : 0;
			var object = {
				min_weight: weight,
				max_weight: weight,
				price: 0,
			}
			if(vm.setting.shipping_express_time > 0)
				object['price_express'] = 0;
			vm.setting.prices.push(object);
		}

		function setUnlimitedWeight() {
			vm.setting.prices[vm.setting.prices.length-1].max_weight = vm.unlimited_weight ? null : 0
		}

	}

	var component = {
		templateUrl: currentHost() + "/js/desktop/settings/shipping/weights-prices/weights-prices.html",
		controller: controller,
		controllerAs: "shippingWeightsPricesCtrl",
		bindings: {
			setting: '<',
			person: '<'
		}
	}

	angular
		.module('todevise')
		.component('shippingWeightsPrices', component);

}());