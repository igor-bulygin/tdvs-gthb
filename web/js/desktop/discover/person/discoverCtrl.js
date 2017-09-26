(function () {
	"use strict";

	function controller(personDataService, UtilService, $scope) {
		var vm = this;
		vm.search = search;
		vm.searchMore = searchMore;
		vm.filters = {};
		vm.maxResults=2;
		vm.page=1;
		vm.results={items: [] };

		init();

		function init() {
		}

		function searchMore() {
			vm.page=vm.page + 1;
			search();
		}

		function search() {
			vm.searching = true;
			if (vm.search_key != vm.key) {
				vm.results={items: [] };
				vm.page=1;
			}
			var params = {
				type: type,
				rand: true,
				limit: vm.maxResults,
				page: vm.page
			}
			function onGetPeopleSuccess(data) {
				vm.results.items=vm.results.items.concat(angular.copy(data.items));
				vm.search_key = angular.copy(vm.key);
				vm.results_found=data.meta.total_count;
				vm.searching = false;
			}

			function onGetPeopleError(err) {
				UtilService.onError(err);
				vm.searching = false;
			}
			if(vm.key)
				params = Object.assign(params, {q: vm.key});

			Object.keys(vm.filters).map(function(filter_type) {
				var new_filter = []
				Object.keys(vm.filters[filter_type]).map(function(filter) {
					if(vm.filters[filter_type][filter])
						new_filter.push(filter);
				})
				if(new_filter.length > 0)
					params[filter_type+'[]'] = new_filter;
			});
			personDataService.getPeople(params, onGetPeopleSuccess, UtilService.onError);
		}

		//watches
		$scope.$watch('discoverCtrl.filters', function (newValue, oldValue) {
			vm.results={items: [] };
			vm.page=1;
			search();
		}, true);

	}

	angular
	.module('discover')
	.controller('discoverCtrl', controller);

}());