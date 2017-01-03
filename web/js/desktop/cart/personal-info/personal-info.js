(function () {
	"use strict";

	function controller(locationDataService) {
		var vm = this;
		vm.user = {};

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