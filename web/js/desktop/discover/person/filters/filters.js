(function() {
	"use strict";

	function controller(UtilService, productDataService, locationDataService, $scope) {
		var vm = this;
		vm.seeMore = seeMore;
		vm.show_countries = 10;
        vm.emitClearFilters = emitClearFilters;

		init();

		function init() {
			getCategories();
			getCountries();
		}

		function getCategories() {
			function onGetCategoriesSuccess(data) {
				vm.categories = angular.copy(data.items);
			}

			var params = {
				scope: 'roots'
			}

			productDataService.getCategories(params, onGetCategoriesSuccess, UtilService.onError);
		}

		function getCountries() {
			function onGetCountriesSuccess(data) {
				vm.countries = angular.copy(data);
			}

			var params = {
				person_type: vm.personType
			}

			locationDataService.getCountry(params, onGetCountriesSuccess, UtilService.onError);
		}

		function seeMore(value) {
			switch (value) {
				case 'countries':
					if (vm.show_countries < vm.countries.meta.total_count) {
                        vm.show_countries += 10;
                    }
					break;
				default:
					break;
			}
		}

        /**
         * Emit event for discoverCtrl.js to clear filters depending on type: clear Theme filters or Country filters
         * @param type - type of filters to be cleared
         */
        function emitClearFilters(type) {
		    switch (type) {
                case 'categories':
                    $scope.$emit('clearFiltersCategories');
                    break;
                case 'countries':
                    $scope.$emit('clearFiltersCountries');
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
			searching: '<',
			filters: '<',
			personType: '<'
		}
	}

	angular
		.module('discover')
		.component('discoverFilters', component);

}());