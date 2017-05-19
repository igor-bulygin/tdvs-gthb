(function () {
	"use strict";

	function controller(locationDataService, UtilService, treeService) {
		var vm = this;
		vm.deleteShippingExpressTime = deleteShippingExpressTime;

		init();

		function init() {
			getCountries();
		}

		function getCountries() {
			function onGetCountriesSuccess(data) {
				vm.countries = parseCountryToNode(data);
			}

			locationDataService.getWorldWide(null, onGetCountriesSuccess, UtilService.onError);
		}

		function deleteShippingExpressTime() {
			delete vm.zone.shipping_express_time;
			vm.zone.prices = vm.zone.prices.map(function(element) {
				if(element.price_express)
					delete element.price_express;
				return element;
			});
		}

		function parseCountryToNode(data) {
			var object = {
				id: data.code || data.country_code,
				text: data.name || data.country_name,
				path: data.path
			}
			if(data.items && angular.isArray(data.items) && data.items.length > 0) {
				object['children'] = data.items.map((element) => {return parseCountryToNode(element)})
			}
			return object;
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