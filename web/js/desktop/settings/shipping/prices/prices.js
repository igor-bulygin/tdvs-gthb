(function () {
	"use strict";

	function controller() {
		var vm = this;
	}

	var component = {
		templateUrl: currentHost() + "/js/desktop/settings/shipping/prices/prices.html",
		controller: controller,
		controllerAs: "shippingPricesCtrl",
		bindings: {}
	}

	angular
		.module('todevise')
		.component('shippingPrices', component);

}());