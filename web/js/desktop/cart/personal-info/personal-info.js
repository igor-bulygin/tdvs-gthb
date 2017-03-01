(function () {
	"use strict";

	function controller(locationDataService, cartDataService, UtilService) {
		var vm = this;
		vm.user = {};
		vm.has_error = UtilService.has_error;
		vm.save = save;

		init();
		function init(){
			getCountries();
			if(angular.isObject(vm.cart.client_info)) {
				for(var key in vm.cart.client_info) {
					vm.user[key] = angular.copy(vm.cart.client_info[key]);
				}
			}
		}

		function getCountries(){
			function onGetCountrySuccess(data) {
				vm.countries = angular.copy(data.items);
			}

			locationDataService.getCountry(null, onGetCountrySuccess, UtilService.onError);
		}

		function save(form){
			function onSaveSuccess(data) {
				vm.cart.client_info = angular.copy(data.client_info);
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
		.module('todevise')
		.component('personalInfo', component);

}());