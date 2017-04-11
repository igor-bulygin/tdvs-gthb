(function () {
	"use strict";

	function controller(UtilService, boxDataService, $scope) {
		var vm = this;
		vm.search = search;
		vm.filters = {};

		init();

		function init() {
			search();
		}

		function search(form) {
			delete vm.results;
			vm.searching = true;
			var params = {
				ignore_empty_boxes: true
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
			})

			function onGetBoxesSuccess(data) {
				vm.searching= false;
				vm.search_key = angular.copy(vm.key);
				vm.results = angular.copy(data);
			}

			function onGetBoxesError(err) {
				UtilService.onError(err);
				vm.searching = false;
			}

			boxDataService.getBoxPub(params, onGetBoxesSuccess, onGetBoxesError);
		}

		//watches
		$scope.$watch('exploreBoxesCtrl.filters', function(newValue, oldValue) {
			search(vm.form)
		}, true);

	}

	angular
		.module('todevise')
		.controller('exploreBoxesCtrl', controller);

}())