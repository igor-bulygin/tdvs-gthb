(function () {
	"use strict";

	function controller(UtilService, productDataService, locationDataService) {
		var vm = this;
		vm.seeMore = seeMore;
		vm.show_countries = 10;

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
				vm.countries = angular.copy(data);
			}

			var params = {
				person_type: type
			}

			locationDataService.getCountry(params, onGetCountriesSuccess, UtilService.onError);
		}

		function seeMore(value) {
			switch(value) {
				case 'countries':
					if(vm.show_countries < vm.countries.meta.total_count)
						vm.show_countries += 10;
					break;
				default:
					break;
			}
		}

	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/discover/person/filters/filters.html',
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