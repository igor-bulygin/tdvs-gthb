(function () {
	"use strict";

	function controller() {
		var vm = this;
	}

	var component = {
		templateUrl: currentHost() + "/js/desktop/settings/shipping/weights/weights.html",
		controller: controller,
		controllerAs: "shippingWeightsCtrl",
		bindings: {}
	}

	angular
		.module('todevise')
		.component('shippingWeights', component);

}());