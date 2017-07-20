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
			//now is cart->shipping_address
			function onSaveSuccess(data) {
				vm.cart.person_info = angular.copy(data.person_info);
				vm.state.state = 3;
			}
			function onSaveError(err) {
				console.log(err);
			}

			form.$submitted = true;
			if(form.$valid) {

				cartDataService.saveUserInfo(vm.user, {id: vm.cart.id},
					onSaveSuccess, onSaveError);
			}
		}

	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/cart/personal-info/personal-info.html',
		controller: controller,
		controllerAs: 'personalInfoCtrl',
		bindings: {
			state: '<',
			cart: '<'
		}
	}

	angular
		.module('cart')
		.component('personalInfo', component);

}());