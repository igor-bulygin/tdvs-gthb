(function () {
	"use strict";

	function controller() {
		var vm = this;
	}

	var component = {
		templateUrl: currentHost() + '/js/util/product-card/product-card.html',
		controller: controller,
		controllerAs: 'productCardCtrl',
		bindings: {
			product: '<'
		}

	}

	angular.module('util')
		.component('productCard', component)

}());