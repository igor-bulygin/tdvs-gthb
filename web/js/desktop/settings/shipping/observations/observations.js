(function () {
	"use strict";

	function controller() {
		var vm = this;
	}

	var component = {
		templateUrl: currentHost() + "/js/desktop/settings/shipping/observations/observations.html",
		controller: controller,
		controllerAs: "shippingObservationsCtrl",
		bindings: {}
	}

	angular
		.module('todevise')
		.component('shippingObservations', component);

}());