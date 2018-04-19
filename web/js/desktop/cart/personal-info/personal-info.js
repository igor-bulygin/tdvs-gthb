(function () {
	"use strict";

	function controller(locationDataService, cartDataService, UtilService) {
		var vm = this;
		vm.person = person;
		vm.has_error = UtilService.has_error;
		vm.save = save;
		vm.editShippingAddress = editShippingAddress;
		vm.adressValidToShip=true;
		init();

		function init(){
			/* activate to multiple countries and unactivate down option
			if (!angular.isUndefined(vm.cart.shipping_address) && vm.cart.shipping_address!=null && !angular.isUndefined(vm.cart.shipping_address.country)) {
				var actualCountry=vm.cart.shipping_address.country;
				var isValid=false;
				vm.countries.forEach(function(country) {
					if (country.id === actualCountry) {
						isValid = true;
					}
				});
				vm.adressValidToShip=isValid;
			}
			*/
			if (!angular.isUndefined(vm.cart.shipping_address) && vm.cart.shipping_address!=null) {
				vm.cart.shipping_address.country= 'ES';
				vm.country_name = 'Spain'
			}
		}

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