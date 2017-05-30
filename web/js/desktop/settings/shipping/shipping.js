(function () {
	"use strict";

	function controller(personDataService, languageDataService, locationDataService, UtilService) {
		var vm = this;
		vm.person = angular.copy(person);
		vm.country_helper = [];
		vm.toggleStatus = toggleStatus;
		vm.save = save;

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
			vm.person.available_countries.forEach(function(code) {
				var setting = vm.person.shipping_settings.find(function(setting) {
					return settings.country_code == code
				})
				if(!setting) {
					vm.person.shipping_settings.push({
						country_code: code
					})
				}
				locationDataService.getCountry({
					countryCode: code
				}, function(data) {
					vm.country_helper.push({
						country_name: data.country_name,
						currency: data.currency_code
					})
				}, UtilService.onError);
			});
		}

		function toggleStatus(index) {
			vm.country_helper[index]['status'] = !vm.country_helper[index]['status'];
		}

		function save() {
			function onUpdateProfileSuccess(data) {
				vm.country_helper.forEach(function(helper) {
					helper.status = false;
				})
			}

			var data = Object.assign({}, {shipping_settings: vm.person.shipping_settings})

			personDataService.updateProfile(data, {
				personId: person.short_id
			}, onUpdateProfileSuccess, UtilService.onError);
		}

	}

	angular
		.module('todevise')
		.controller('shippingSettingsCtrl', controller);
}());