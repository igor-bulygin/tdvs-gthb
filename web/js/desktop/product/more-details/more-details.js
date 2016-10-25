(function () {
	"use strict";

	function controller(){
		var vm = this;
		vm.description_language = 'en-US';
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/product/more-details/more-details.html',
		controller: controller,
		controllerAs: 'productMoreDetailsCtrl',
		bindings: {
			product: '<',
			languages: '='
		}
	}

	angular
		.module('todevise')
		.component('productMoreDetails', component);

}());