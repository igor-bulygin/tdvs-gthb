(function () {
	"use strict";

	function controller() {
		var vm = this;
		

		//watchs
		
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/product/variations/variations.html',
		controller: controller,
		controllerAs: 'productVariationsCtrl',
		bindings: {
			product: '<'
		}
	}

	angular
		.module('todevise')
		.component('productVariations', component);
}());