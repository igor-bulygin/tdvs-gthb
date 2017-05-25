(function () {
	"use strict";

	function controller(metricDataService, UtilService) {
		var vm = this;
		vm.addPrice = addPrice;

		init();

		function init() {
			getMetrics();
			getCurrencies();
			if(!angular.isArray(vm.setting.prices) || vm.setting.prices.length == 0) {
				vm.setting.prices = []
			}
		}

		function getMetrics() {
			function onGetMetricsSuccess(data) {
				vm.weights = angular.copy(data.weight);
			}

			metricDataService.getMetric(null, onGetMetricsSuccess, UtilService.onError);
		}

		function getCurrencies() {
			function onGetCurrenciesSuccess(data) {
				vm.currencies = angular.copy(data);
			}

			metricDataService.getCurrencies(null, onGetCurrenciesSuccess, UtilService.onError)
		}

		function addPrice() {
			var object = {
				min_weight: 0,
				max_weight: 0,
				price: 0,
			}
			if(vm.setting.shipping_express_time > 0)
				object['price_express'] = 0;
			vm.setting.prices.push(object);
		}

	}

	var component = {
		templateUrl: currentHost() + "/js/desktop/settings/shipping/weights-prices/weights-prices.html",
		controller: controller,
		controllerAs: "shippingWeightsPricesCtrl",
		bindings: {
			setting: '<',
		}
	}

	angular
		.module('todevise')
		.component('shippingWeightsPrices', component);

}());