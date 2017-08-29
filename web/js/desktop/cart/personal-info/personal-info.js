(function () {
	"use strict";

	function controller(locationDataService, cartDataService, UtilService) {
		var vm = this;
		vm.person = person;
		vm.has_error = UtilService.has_error;
		vm.save = save;
		vm.editShippingAddress = editShippingAddress;

		init();

		function init(){}

		function save(form){
			form.$submitted = true;
			if(form.$valid) {
				vm.state = 2;
			}
		}

		function editShippingAddress() {
			vm.state = 1;
		}

	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/cart/personal-info/personal-info.html',
		controller: controller,
		controllerAs: 'personalInfoCtrl',
		bindings: {
			state: '=?',
			cart: '<',
			countries: '<'
		}
	}

	angular
		.module('cart')
		.component('personalInfo', component);

}());