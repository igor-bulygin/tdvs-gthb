(function () {
	"use strict";

	function controller(UtilService, productDataService, locationDataService) {
		var vm = this;

		init();

		function init(){
			getCategories();
			getCountries();
		}

		function getCategories(){
			function onGetCategoriesSuccess(data) {
				vm.categories = angular.copy(data.items);
			}

			var params = {
				scope: 'roots'
			}

			productDataService.getCategories(params, onGetCategoriesSuccess, UtilService.onError);
		}

		function getCountries(){
			function onGetCountriesSuccess(data) {
				vm.countries = angular.copy(data.items);
			}

			var params = {
				person_type: type
			}

			locationDataService.getCountry(params, onGetCountriesSuccess, UtilService.onError);
		}

	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/discover/filters/filters.html',
		controller: controller,
		controllerAs: 'discoverFiltersCtrl',
		bindings: {
			filters : '<'
		}
	}

	angular
		.module('todevise')
		.component('discoverFilters', component);

}());