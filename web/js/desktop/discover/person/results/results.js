(function () {
	"use strict";

	function controller($scope, personDataService, UtilService) {
		var vm = this;
		vm.addMoreItems = addMoreItems;
		vm.setFollow = setFollow;
		var show_items = 6;
		vm.personId = '8216520';

		function addMoreItems() {
			var last = vm.results_infinite.length;
			vm.results_infinite = vm.results_infinite.concat(vm.results.items.slice(last, last+show_items));
		}

		function setFollow(item) {
			function onSetFollowSuccess(data) {
					if (item.followed) {
						item.followed = false;
					} 
					else {
						item.followed = true;
					}
				}
				function onSetFollowError(err) {
					UtilService.onError(err);
				}
			var params = {
				personToFollowId: item.id,
				personId: vm.personId
			}
				personDataService.followPerson(params, params, onSetFollowSuccess, onSetFollowError);
		}

		$scope.$watch('discoverResultsCtrl.results', function(newValue, oldValue) {
			if(angular.isObject(newValue)) {
				if(newValue.items.length > 0)
					vm.results_infinite = newValue.items.slice(0, show_items);
				else {
					vm.results_infinite = [];
				}
			}
		}, true)
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/discover/person/results/results.html',
		controller: controller,
		controllerAs: 'discoverResultsCtrl',
		bindings: {
			results: '<'
		}
	}

	angular
		.module('discover')
		.component('discoverResults', component);

}());