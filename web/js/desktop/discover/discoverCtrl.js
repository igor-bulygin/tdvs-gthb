(function () {
	"use strict";

	function controller(personDataService, UtilService, $scope) {
		var vm = this;
		vm.search = search;
		vm.filters = {}

		init();

		function init() {
			search(vm.form);
		}

		function search(form) {
			var params = {
				type: type
			}
			function onGetPeopleSuccess(data) {
				vm.search_key = angular.copy(vm.key);
				vm.results = angular.copy(data);
			}

			if(vm.key)
				params = Object.assign(params, {q: vm.key});

			Object.keys(vm.filters).map(function(filter_type) {
				var new_filter = []
				Object.keys(vm.filters[filter_type]).map(function(filter) {
					if(vm.filters[filter_type][filter])
						new_filter.push(filter);
				})
				params[filter_type+'[]'] = new_filter;
			})

			personDataService.getPeople(params, onGetPeopleSuccess, UtilService.onError);
		}

		//watches
		$scope.$watch('discoverCtrl.filters', function (newValue, oldValue) {
			if(!angular.equals(newValue, {}))
				search(vm.form)
		}, true);

	}

	angular.module('todevise')
		.controller('discoverCtrl', controller);

}());