(function () {
	"use strict";

	function controller(locationDataService, cartDataService, UtilService) {
		var vm = this;
		vm.user = new cartDataService.CartClientInfo;
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
			locationDataService.Country.get()
			.$promise.then(function(dataCountries) {
				vm.countries = angular.copy(dataCountries.items);
			 }, function (err) {
			 	//err
			 	console.log(err);
			 })
		}

		function save(form){
			form.$submitted = true;
			if(form.$valid) {
				vm.user.$save({
					id: vm.cart.id
				}).then(function(dataSaved) {
					vm.cart.client_info = angular.copy(dataSaved.client_info);
					vm.state.state=3;
				}, function (err) {
					//TODO: Show err
				});
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