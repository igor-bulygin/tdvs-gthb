(function () {
	"use strict";

	function controller(locationDataService, UtilService) {
		var vm = this;
		vm.deleteShippingExpressTime = deleteShippingExpressTime;

		init();

		function init() {
			getCountries();
		}

		function getCountries() {
			function onGetCountriesSuccess(data) {
				vm.countries = angular.copy(data.items);
			}

			locationDataService.getCountry(null, onGetCountriesSuccess, UtilService.onError);
		}

		function deleteShippingExpressTime() {
			delete vm.zone.shipping_express_time;
			vm.zone.prices = vm.zone.prices.map(function(element) {
				if(element.price_express)
					delete element.price_express;
				return element;
			});
		}
	}

	var component = {
		templateUrl: currentHost() + "/js/desktop/settings/shipping/zones/zones.html",
		controller: controller,
		controllerAs: "shippingZonesCtrl",
		bindings: {
			zone: '<',
		}
	}

	angular
		.module('todevise')
		.component('shippingZones', component);

}());