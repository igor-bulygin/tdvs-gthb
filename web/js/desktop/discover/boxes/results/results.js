(function () {
	"use strict";

	function controller($scope) {
		var vm = this;
		vm.addMoreItems = addMoreItems;
        vm.countLoved = countLoved;
		var show_items = 6;

		function addMoreItems() {
			var last = vm.results_infinite.length;
			vm.results_infinite = vm.results_infinite.concat(vm.results.items.slice(last, last+show_items));
		}

		$scope.$watch('exploreBoxesResultsCtrl.results', function(newValue, oldValue) {
			if(angular.isObject(newValue)) {
				if(newValue.items.length > 0) {
                    vm.results_infinite = newValue.items.slice(0, show_items);
                }
				else {
					vm.results_infinite = [];
				}
			}
		}, true);
	}

	function countLoved(products) {
	    return products.reduce(function (prev, cur) {
            return parseInt((cur.loveds || 0)) + prev;
        }, 0);
    }



	var component = {
		templateUrl: currentHost() + '/js/desktop/discover/boxes/results/results.html',
		controller: controller,
		controllerAs: 'exploreBoxesResultsCtrl',
		bindings: {
			results: '<'
		}
	}

	angular
		.module('discover')
		.component('exploreBoxesResults', component);

}());