(function () {
	'use strict';

	function controller(personDataService, UtilService, $scope) {
		var vm = this;
		vm.search = search;
		vm.searchMore = searchMore;
		vm.filters = {};
		vm.maxResults=100;
		vm.page=1;
		vm.results={items: [] };

		init();

		function init() {
			if (!vm.searchdata) {
				vm.searchdata = { hideHeader:false, key: ''};
			}
		}

		function searchMore() {
			vm.page = vm.page + 1;
			search();
		}

		function search() {
			if (!vm.searching) {
				vm.searching = true;
				if (vm.search_key != vm.searchdata.key) {
					vm.results={items: [] };
					vm.page=1;
				}
				var params = {
					type: vm.searchdata.personType,
					rand: true,
					limit: vm.maxResults,
					page: vm.page
				}
				var onGetPeopleSuccess = function(data) {
					vm.results.items=vm.results.items.concat(angular.copy(data.items));
					vm.search_key = angular.copy(vm.searchdata.key);
					vm.results_found=data.meta.total_count;
					vm.searching = false;
				}

				var onGetPeopleError = function(err) {
					UtilService.onError(err);
					vm.searching = false;
				}
				if(vm.searchdata.key) {
                    params = Object.assign(params, {q: vm.searchdata.key});
                }

				Object.keys(vm.filters).map(function(filter_type) {
					var new_filter = []
					Object.keys(vm.filters[filter_type]).map(function(filter) {
						if(vm.filters[filter_type][filter]) {
                            new_filter.push(filter);
                        }
					})
					if(new_filter.length > 0) {
                        params[filter_type + '[]'] = new_filter;
                    }
				});
				personDataService.getPeople(params, onGetPeopleSuccess, UtilService.onError);
			}
		}

		//watches
		$scope.$watch('discoverCtrl.filters', function (newValue, oldValue) {
			vm.results={items: [] };
			vm.page=1;
			search();
		}, true);

        /**
         * Watch event generated in filters.js when categories filters are cleared
         */
		$scope.$on("clearFiltersCategories", function(evt,data) {
            vm.filters.categories = {};
        }, true);

        /**
         * Watch event generated in filters.js when countries filters are cleared
         */
        $scope.$on("clearFiltersCountries", function(evt,data) {
            vm.filters.countries = {};
        }, true);


	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/discover/person/explore-person.html',
		controller: controller,
		controllerAs: 'discoverCtrl',
		bindings: {
			searchdata: '<?'
		}
	}

	angular
		.module('discover')
		.component('explorePerson', component);

	

}());