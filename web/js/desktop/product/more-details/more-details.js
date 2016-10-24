(function () {
	"use strict";

	function controller(){
		var vm = this;
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/product/more-details/more-details.html',
		controller: controller,
		controllerAs: 'productMoreDetailsCtrl',
		bindings: {
			product: '<'
		}
	}

	angular
		.module('todevise')
		.component('productMoreDetails', component);

}());