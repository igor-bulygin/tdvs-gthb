(function () {
	"use strict";

	function controller(locationDataService, cartDataService, UtilService) {
		var vm = this;
		vm.user = new cartDataService.CartClientInfo;
		vm.save = save;

		init();
		function init(){
			getCountries();
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
					//TODO: go to step 3
				}, function (err) {
					console.log(err);
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