(function () {
	"use strict";

	function controller(personDataService, languageDataService, locationDataService, UtilService) {
		var vm = this;
		vm.person = angular.copy(person);
		vm.toggleStatus = toggleStatus;
		vm.country_helper = [];

		init();
		
		function init() {
			getLanguages();
			getCountries();
		}

		function getLanguages() {
			function onGetLanguagesSuccess(data) {
				vm.languages = angular.copy(data.items);
			}

			languageDataService.getLanguages(onGetLanguagesSuccess, UtilService.onError);
		}

		function getCountries() {
			function onGetCountrySuccess(data) {
				vm.countries = angular.copy(data.items);
				checkCountries();
				parseCountries();
			}
			locationDataService.getCountry(null, onGetCountrySuccess, UtilService.onError);
		}

		function checkCountries() {
			if(vm.person.available_countries.length > vm.person.shipping_settings.length) {
				vm.person.available_countries.forEach(function(code) {
					var setting = vm.person.shipping_settings.find(function(settings) {
						return settings.country_code == code
					})
					if(!setting) {
						vm.person.shipping_settings.push({
							country_code: code
						})
					}
				})
			}
		}

		function parseCountries() {
			if(vm.person.shipping_settings.length > 0) {
				vm.person.shipping_settings.forEach(function (setting) {
					var country = vm.countries.find(function(country) {
						return setting.country_code == country.id
					})
					vm.country_helper.push({
						country_name: country.country_name
					})
				})
			}
		}

		function toggleStatus(index) {
			vm.country_helper[index]['status'] = !vm.country_helper[index]['status'];
		}

	}

	angular
		.module('todevise')
		.controller('shippingSettingsCtrl', controller);
}());