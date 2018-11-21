(function() {
	"use strict";

	function controller(UtilService, productDataService, locationDataService, $scope) {
		var vm = this;
		vm.seeMore = seeMore;
		vm.show_countries   = 4;
        vm.show_categories  = 4;
		vm.categories_all   = [];
        vm.expandedFilters  = []; // filters that are expanded
        vm.countries_loaded = false;
        vm.filters_loaded   = false;


        vm.emitClearFilters = emitClearFilters;
        vm.setPersonFilters = setPersonFilters;
        vm.setCategoriesFilters = setCategoriesFilters;
        vm.setLocationsFilters = setLocationsFilters;
        vm.emitSearch = emitSearch;
        vm.expandFilter = expandFilter;

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

        /**
         * get all countries. If they're loaded and if there's search results loaded - call function fot make filters
         */
		function getCountries() {
			function onGetCountriesSuccess(data) {
				vm.countries_all = angular.copy(data);
				vm.countries_loaded = true;
				if (vm.results !== undefined) {
				    vm.setPersonFilters();
                }
			}

			var params = {
				person_type: vm.personType
			}

			locationDataService.getCountry(params, onGetCountriesSuccess, UtilService.onError);
		}

        /**
         * disabled because of short list of countries, CSS is enough
         * @param value
         */
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
         * Finction that increases/decreases height of block with filters
         * @param filter - name of filters set ('categories', 'countries', etc)
         * @param expand - if true - size of block increases, if false - decreases
         */
		function expandFilter(filter, expand) {
            if (expand) {
                if (vm.expandedFilters.indexOf(filter) === -1) {
                    vm.expandedFilters.push(filter);
                }
            }
            else {
                var index = vm.expandedFilters.indexOf(filter);
                vm.expandedFilters.splice(index, 1);
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

        /**
         * function that emits event for new search in parent controller (discoverCtrl). Is called from HTML (filters.html) on checkbox change
         * @param resetFilters
         */
        function emitSearch(resetFilters) {
            $scope.$emit('emitSearch', resetFilters);
        }

        /**
         * Function mnakes arrays for filters.
         */
        function setPersonFilters() {
            if (vm.countries_loaded) {
                vm.setCategoriesFilters();
                vm.setLocationsFilters();
                vm.filters_loaded = true;
            }
        }

        /**
         * makes Categories filters.
         */
        function setCategoriesFilters() {
            vm.categories = {};
            vm.categories.items = [];
            var cats = [];
            // console.log(vm.results);

            vm.results.forEach(function(person) {
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

        function setLocationsFilters() {
            vm.locations = {};
            vm.locations.items = [];
            var locs = [];

            vm.results.forEach(function(person) {
                /**
                 * retrieve information about countries IDs in persons found
                 */
                locs.push(person.country);
            });

            /**
             * retrieve FULL information about country in persons found (filter all countries according to earlier found countries IDs)
             */
            var exists = [];
            vm.countries = vm.countries_all.items.filter(function (item) {
                if (exists.indexOf(item.id) === -1 && locs.indexOf(item.id) > -1) {
                    exists.push(item.id);
                    return true;
                }
                return false;
            });
        }


        $scope.$on("setPersonFilters", function(evt, data) {
            vm.results = data;
            vm.setPersonFilters();
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