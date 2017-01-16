(function () {
	"use strict";

	function controller(UtilService, deviserDataService, locationDataService) {
		var vm = this;
		vm.bank_location = [{
			country_name: 'Canada',
			country_code: 'CA'
		}, {
			country_name: 'New Zealand',
			country_code: 'NZ'
		}, {
			country_name: 'United States',
			country_code: 'US'
		}, {
			country_name: 'Rest of the world',
			country_code: 'OTHER'
		}]
		vm.bank_information = {
			location: 'OTHER'
		}
		vm.saveBankInformation = saveBankInformation;

		init();

		function init(){
			getDeviser();
			getCountries();
		}

		function getDeviser() {
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function(dataDeviser) {
					vm.deviser = angular.copy(dataDeviser);
				}, function(err){
					//err
				});
		}

		function getCountries(){
			locationDataService.Country.get()
				.$promise.then(function(dataCountries) {
					vm.countries = angular.copy(dataCountries.items);
				}, function(err) {
					//err
				})
		}

		function saveBankInformation(form){
			console.log(form.$valid);
			form.$setSubmitted();
			if(form.$valid) {
				if(!UtilService.isObject(vm.deviser.settings)) {
					vm.deviser.settings = {};
				}
				vm.deviser.settings.bank_information = angular.copy(vm.bank_information);
				vm.deviser.deviser_id = vm.deviser.id;
				vm.deviser.$update().then(function (dataDeviser) {
					vm.deviser = angular.copy(dataDeviser);
				})
			} else {

			}
		}
	}

	angular.module('todevise')
		.controller('billingCtrl', controller);

}());