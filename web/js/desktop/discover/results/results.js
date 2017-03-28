(function () {
	"use strict;"

	function controller($scope) {
		var vm = this;
		vm.addMoreItems = addMoreItems;
		var show_items = 6;

		function addMoreItems() {
			var last = vm.results_infinite.length;
			vm.results_infinite = vm.results_infinite.concat(vm.results.items.slice(last, last+show_items));
		}

		$scope.$watch('discoverResultsCtrl.results', function(newValue, oldValue) {
			if(angular.isObject(newValue) && newValue.items.length > 0) {
				vm.results_infinite = newValue.items.slice(0, show_items);
			}
		}, true)
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/discover/results/results.html',
		controller: controller,
		controllerAs: 'discoverResultsCtrl',
		bindings: {
			results: '<'
		}
	}

	angular
		.module('todevise')
		.component('discoverResults', component);

}());