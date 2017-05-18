(function () {
	"use strict";

	function controller(personDataService,UtilService,locationDataService) {
		var vm = this;
		vm.person = {id:person.id, personal_info:angular.copy(person.personal_info), settings:angular.copy(person.settings)};
		vm.city = vm.person.personal_info.city + ', ' + vm.person.personal_info.country;
		init();
		vm.update=update;
		vm.saving=false;
		vm.saved=false;
		vm.existRequiredError=existRequiredError;
		vm.searchPlace=searchPlace;
		vm.selectCity=selectCity;
		vm.showCities = false;
		
		function init() {
			getCurrencies();
		}

		function getCurrencies() {
			function onGetCurrenciesSuccess(data) {
				vm.currencies = data;
			}
			personDataService.getCurrencies(onGetCurrenciesSuccess, UtilService.onError);
		}

		function searchPlace(place) {
			if (place.length>2) {
				function onGetLocationSuccess(data) {
					if(data.items.length === 0) {
						vm.showCities = false;
					}
					if(data.items.length > 0) {
						vm.showCities = true;
						vm.cities = angular.copy(data.items);
					}
				}
				if(place)
					locationDataService.getLocation({q: place}, onGetLocationSuccess, UtilService.onError);
				else {
					selectCity({
						city: null,
						country_code: null
					})
				}
			}
		}

		function selectCity(city) {
			vm.person.personal_info.city = city.city;
			vm.person.personal_info.country = city.country_code;
			if(city.city && city.country_code)
				vm.city = vm.person.personal_info.city + ', ' + vm.person.personal_info.country;
			vm.showCities = false;
		}

		function update() {
			if (!vm.dataForm.$valid) {
				for (var i = array.length - 1; i > -1; i--) {
					if (array[i].name === "zipCode")
						array.splice(i, 1);
				}
			}
			else {
				vm.saving=true;
				function onUpdateGeneralSettingsSuccess(data) {
					vm.saving=false;
					vm.saved=true;
					vm.dataForm.$dirty=false;
				}

				function onUpdateGeneralSettingsError(data) {
					vm.saving=false;
				}
				personDataService.updateProfile(vm.person,{personId: vm.person.id}, onUpdateGeneralSettingsSuccess, onUpdateGeneralSettingsError);
			}
		}

		function existRequiredError(fieldName) {
			var exists=false;
			angular.forEach(vm.dataForm.$error.required, function(value){
				if (!angular.isUndefined(value.$name) && value.$name==fieldName) {
					exists=true;
				}
			});
			return exists;
		}
	}

	angular	
	.module('todevise')
	.controller('generalSettingsCtrl', controller);

}());