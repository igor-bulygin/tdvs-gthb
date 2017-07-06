(function () {
	"use strict";

	function controller(personDataService, languageDataService, locationDataService, UtilService, $window) {
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
				var setting_found = vm.person.shipping_settings.find(function(setting) {
					return setting.country_code == code
				})
				if(!setting_found) {
					vm.person.shipping_settings.push({
						country_code: code
					})
				}
				locationDataService.getCountry({
					countryCode: code
				}, function(data) {
					var newCountry = {
						country_name: data.country_name,
						currency: data.currency_code
					}
					newCountry['status'] = UtilService.isDraft(vm.person) ? true : false;
					vm.country_helper.push(newCountry);
				}, UtilService.onError);
			});
		}

		function toggleStatus(index) {
			vm.country_helper[index]['status'] = !vm.country_helper[index]['status'];
		}

		function save() {
			function onUpdateProfileSuccess(returnData) {
				if(UtilService.isPublic(vm.person)) {
					vm.country_helper.forEach(function(helper) {
						helper.status = false;
					})
				}
				if(UtilService.isDraft(vm.person))
					$window.location.href = returnData.main_link;
			}

			var data = Object.assign({}, {shipping_settings: vm.person.shipping_settings})

			personDataService.updateProfile(data, {
				personId: person.short_id
			}, onUpdateProfileSuccess, UtilService.onError);
		}

	}

	angular
		.module('settings')
		.controller('shippingSettingsCtrl', controller);
}());