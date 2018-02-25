(function () {
	"use strict";

	function controller(UtilService, boxDataService, $scope) {
		var vm = this;
		vm.search = search;
		vm.searchMore = searchMore;
		vm.filters = {};
		vm.maxResults= 100;
		vm.page=1;
		vm.results={items: [] };

		init();

		function init() {
			if (!vm.searchdata) {
				vm.searchdata = { hideHeader:false, key: ''};
			}
		}

		function searchMore() {
			vm.page=vm.page + 1;
			search();
		}

		function search() {
			if (vm.search_key != vm.searchdata.key) {
				vm.results={items: [] };
				vm.page=1;
			}
			vm.searching = true;
			var params = {
				ignore_empty_boxes: true,
				limit: vm.maxResults,
				page: vm.page
			}
			if(vm.searchdata.key)
				params = Object.assign(params, {q: vm.searchdata.key});
			
			Object.keys(vm.filters).map(function(filter_type) {
				var new_filter = []
				Object.keys(vm.filters[filter_type]).map(function(filter) {
					if(vm.filters[filter_type][filter])
						new_filter.push(filter);
				})
				if(new_filter.length > 0)
					params[filter_type+'[]'] = new_filter;
			})

			function onGetBoxesSuccess(data) {
				vm.results_found=data.meta.total_count;
				vm.search_key = angular.copy(vm.searchdata.key);
				vm.results.items=vm.results.items.concat(angular.copy(data.items));
				vm.searching= false;
			}

			function onGetBoxesError(err) {
				UtilService.onError(err);
				vm.searching = false;
			}

			boxDataService.getBoxPub(params, onGetBoxesSuccess, onGetBoxesError);
		}

		//watches
		$scope.$watch('exploreBoxesCtrl.filters', function(newValue, oldValue) {
			vm.results={items: [] };
			vm.page=1;
			search();
		}, true);

	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/discover/boxes/explore-boxes.html',
		controller: controller,
		controllerAs: 'exploreBoxesCtrl',
		bindings: {
			searchdata: '<?'
		}
	}

	angular
		.module('discover')
		.component('exploreBoxes', component);

}())