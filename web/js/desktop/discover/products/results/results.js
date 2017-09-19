(function () {
	"use strict";

	function controller($scope, UtilService) {
		var vm = this;
		var show_items = 32;
		vm.truncateString = UtilService.truncateString;
		vm.addMoreItems = addMoreItems;
		vm.results_infinite = [];
		vm.searchPage=1;

		function addMoreItems() {
			var last = vm.results_infinite.length;
			if ((last+show_items) > (vm.limit * vm.searchPage)) {
				$scope.$emit("changeNewPage",true); 
				vm.searchPage= vm.searchPage + 1;
			}
			else {
				vm.results_infinite = vm.results_infinite.concat(vm.results.slice(last, last+show_items));
			}
		}


		$scope.$watch('exploreProductsResultsCtrl.results', function(newValue, oldValue) {
			if (newValue===-1) {
				vm.results_infinite = [];
			}
			if(angular.isObject(newValue)) {
				vm.results_infinite=vm.results_infinite.concat(vm.results.slice(vm.results_infinite.length, vm.results_infinite.length + show_items));
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