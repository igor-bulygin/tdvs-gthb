(function () {
	"use strict";

	function controller(locationDataService, cartDataService, UtilService) {
		var vm = this;
		vm.person = person;
		vm.has_error = UtilService.has_error;
		vm.save = save;

		init();
		function init(){
			getCountries();
		}

		function getCountries(){
			function onGetCountrySuccess(data) {
				vm.countries = angular.copy(data.items);
			}

			locationDataService.getCountry(null, onGetCountrySuccess, UtilService.onError);
		}

		function save(form){
			form.$submitted = true;
			if(form.$valid) {
				vm.state = 2;
			}
		}

	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/cart/personal-info/personal-info.html',
		controller: controller,
		controllerAs: 'personalInfoCtrl',
		bindings: {
			state: '=?',
			cart: '<'
		}
	}

	angular
		.module('cart')
		.component('personalInfo', component);

}());