(function () {
	"use strict";

	function controller() {
		var vm = this;

	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/cart/summary/summary.html',
		controller: controller,
		controllerAs: 'summaryCtrl',
		bindings: {
			cart: '<',
			devisers: '<'
		}
	}

	angular
		.module('todevise')
		.component('cartSummary', component);

}());