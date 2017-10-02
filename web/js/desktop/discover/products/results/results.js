(function () {
	"use strict";

	function controller($scope, UtilService) {
		var vm = this;
		var show_items = 6;
		vm.truncateString = UtilService.truncateString;
		vm.addMoreItems = addMoreItems;
		vm.results_infinite = [];
		vm.searchPage=1;
		addMoreItems();

		function addMoreItems() {
			var last = vm.results_infinite.length;
			vm.results_infinite = vm.results_infinite.concat(vm.results.items.slice(last, last+show_items));
		}

		$scope.$watch('exploreProductsResultsCtrl.results', function(newValue, oldValue) {
			if(angular.isObject(newValue)) {
				if(newValue.length > 0)
					vm.results_infinite = newValue.slice(0, show_items);
				else {
					vm.results_infinite = [];
				}
			}
		}, true);
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/discover/products/results/results.html',
		controller: controller,
		controllerAs: 'exploreProductsResultsCtrl',
		bindings: {
			results: '<',
			limit:'<'
		}
	}

	angular
	.module('discover')
	.component('exploreProductsResults', component);

}());