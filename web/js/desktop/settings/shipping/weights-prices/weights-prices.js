(function () {
	"use strict";

	function controller(UtilService, locationDataService, $scope) {
		var vm = this;
		vm.addPrice = addPrice;
		vm.deletePrice = deletePrice;
		vm.setUnlimitedWeight = setUnlimitedWeight;
		vm.has_error = UtilService.has_error;

		init();

		function init() {
			if(!angular.isArray(vm.setting.prices) || vm.setting.prices.length == 0) {
				vm.setting.prices = []
			}
			else {
				if(vm.setting.prices[vm.setting.prices.length-1].max_weight === null)
					vm.unlimited_weight = true;
			}
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

		function deletePrice(index) {
			vm.setting.prices.splice(index, 1);
		}

		function setUnlimitedWeight() {
			vm.setting.prices[vm.setting.prices.length-1].max_weight = vm.unlimited_weight ? null : 0
		}

		$scope.$watch('shippingWeightsPricesCtrl.pricesForm.$error', function(newValue, oldValue){
			if(angular.isObject(newValue) && newValue !== null) {
				for(var key in newValue) {
					if(key === 'min') {
						newValue[key].forEach(function(field) {
							var name = field['$name'];
							vm.pricesForm[name].$setTouched();
						});
					}
				}
			}
		}, true)

	}

	var component = {
		templateUrl: currentHost() + "/js/desktop/settings/shipping/weights-prices/weights-prices.html",
		controller: controller,
		controllerAs: "shippingWeightsPricesCtrl",
		bindings: {
			setting: '<',
			person: '<',
			currency: '<'
		}
	}

	angular
		.module('settings')
		.component('shippingWeightsPrices', component);

}());