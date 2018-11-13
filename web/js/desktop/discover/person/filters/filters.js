(function() {
	"use strict";

	function controller(UtilService, productDataService, locationDataService, $scope) {
		var vm = this;
		vm.seeMore = seeMore;
		vm.show_countries = 10;
		vm.categories_all = [];
        vm.emitClearFilters = emitClearFilters;
        vm.setPersonFilters = setPersonFilters;
        vm.emitSearch = emitSearch;

		init();

		function init() {
			getCategories();
			getCountries();
		}

		function getCategories() {
			function onGetCategoriesSuccess(data) {
				vm.categories_all = angular.copy(data.items);
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

        function emitSearch(resetFilters) {
            $scope.$emit('emitSearch', resetFilters);
        }

        function setPersonFilters(results) {
            vm.categories = {};
            vm.categories.items = [];
            var cats = [];
            // console.log(vm.results.items);

            results.forEach(function(person) {
                /**
                 * retrieve information about categories IDs in persons found
                 */
                cats = cats.concat(angular.copy(person.categories));
            });

            /**
             * retrieve FULL information about categories in products found (filter all categories according to earlier found categories IDs)
             */
            var exists = [];
            vm.categories = vm.categories_all.filter(function (item) {
                if (exists.indexOf(item.short_id) === -1 && cats.indexOf(item.short_id) > -1) {
                    exists.push(item.short_id);
                    return true;
                }
                return false;
            });
        }


        $scope.$on("setPersonFilters", function(evt, data) {
            vm.setPersonFilters(data);
        }, true);


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