(function () {
	"use strict";

	function controller() {
		var vm = this;
	}

	var component = {
		templateUrl: currentHost() + "/js/desktop/settings/shipping/zones/zones.html",
		controller: controller,
		controllerAs: "shippingZonesCtrl",
		bindings: {}
	}

	angular
		.module('todevise')
		.component('shippingZones', component);

}());