(function() {
	"use strict";

	function controller(UtilService, storyDataService, $scope,$sce) {
		var vm = this;
		vm.search = search;
		vm.filters = {};
		init();

		function init() {
			search();
		}

		function search(form) {
			if (!vm.searching) {
				vm.searching = true;
				delete vm.results;
				var params={};
				if (vm.key)
					params = Object.assign(params, { q: vm.key });

				Object.keys(vm.filters).map(function(filter_type) {
					var new_filter = []
					Object.keys(vm.filters[filter_type]).map(function(filter) {
						if (vm.filters[filter_type][filter]) {
                            new_filter.push(filter);
                        }
					})
					if (new_filter.length > 0)
						params[filter_type + '[]'] = new_filter;
				})

				function onGetStoriesSuccess(data) {
					vm.searching = false;
					vm.search_key = angular.copy(vm.key);
					vm.results = angular.copy(data);
					angular.forEach(vm.results.items, function(value){
						if (!angular.isUndefined(value.first_text)) {
							value.first_text = $sce.trustAsHtml(value.first_text);
						}
					});
				}
				function onGetStoriesError(err) {
					UtilService.onError(err);
					vm.searching = false;
				}
				storyDataService.getStoryPub(params, onGetStoriesSuccess, onGetStoriesError);
			}
		}

//watches
$scope.$watch('exploreStoriesCtrl.filters', function(newValue, oldValue) {
	search(vm.form)
}, true);
}

angular
	.module('discover')
	.controller('exploreStoriesCtrl', controller);

}())