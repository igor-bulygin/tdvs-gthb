(function() {
	"use strict";

	function controller(UtilService, locationDataService, productDataService, languageDataService, $scope) {
		var vm = this;
		vm.has_error = UtilService.has_error;
		vm.stripHTMLTags = UtilService.stripHTMLTags;
		vm.person = person;
		console.log(person);
		vm.description_language = vm.biography_language = 'en-US';
		vm.limit_text_short_description = 140;
		vm.searchPlace = searchPlace;
		vm.selectCity = selectCity;

		init();

		function init() {
			getCategories();
			getLanguages();
		}

		function getCategories() {
			function onGetCategoriesSuccess(data) {
				if(data.items && angular.isArray(data.items) && data.items.length > 0)
					vm.categories = data.items
			}

			productDataService.getCategories({scope: 'roots'}, onGetCategoriesSuccess, UtilService.onError);
		}

		function getLanguages() {
			function onGetLanguagesSuccess(data) {
				if(data.items && angular.isArray(data.items) && data.items.length > 0) {
					vm.languages = data.items;
				}
			}

			languageDataService.getLanguages(onGetLanguagesSuccess, UtilService.onError);
		}

		function searchPlace(place) {
			function onGetLocationSuccess(data) {
				if(data.items && angular.isArray(data.items)) {
					vm.showCities = data.items.length > 0 ? true : false;
					if(data.items.length > 0)
						vm.cities = angular.copy(data.items);
				}
			}

			if(place)
				locationDataService.getLocation({q: place}, onGetLocationSuccess, UtilService.onError);
			else {
				selectCity({
					city: null,
					country_code: null
				});
			}
		}

		function selectCity(city) {
			vm.person.personal_info.city = city.city;
			vm.person.personal_info.country = city.country_code;
			if(city.city && city.country_code)
				vm.city = vm.person.personal_info.city + ', ' + vm.person.personal_info.country;
			vm.showCities = false;
		}

		//watches
		$scope.$watch('completeProfileCtrl.person.text_short_description[completeProfileCtrl.description_language]', function(newValue, oldValue) {
			if(newValue && newValue.length > vm.limit_text_short_description)
				vm.person.text_short_description[vm.description_language] = oldValue;
		});

	}

	angular
		.module('todevise')
		.controller('completeProfileCtrl', controller);

}());