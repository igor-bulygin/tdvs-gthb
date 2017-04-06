(function () {
	"use strict";

	function controller(personDataService, UtilService, $scope) {
		var vm = this;
		vm.search = search;
		vm.filters = {};

		init();

		function init() {
			search(vm.form);
		}

		/* use init_search to save all devisers on init and minimize calls to server*/
		function search(form) {
			delete vm.results;
			vm.searching = true;
			var params = {
				type: type,
				rand: true
			}
			function onGetPeopleSuccess(data) {
				vm.searching = false;
				vm.search_key = angular.copy(vm.key);
				vm.results = angular.copy(data);
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
			})

			personDataService.getPeople(params, onGetPeopleSuccess, UtilService.onError);
		}

		//watches
		$scope.$watch('discoverCtrl.filters', function (newValue, oldValue) {
			search(vm.form)
		}, true);

	}

	angular
	.module('todevise')
	.controller('discoverCtrl', controller);

}());