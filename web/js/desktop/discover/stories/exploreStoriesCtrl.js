(function () {
	"use strict";

	function controller(UtilService, storyDataService, $scope) {
		var vm = this;
		vm.search = search;

		init();

		function init() {
			search();
		}

		function search(form) {
			delete vm.results;
			vm.searching = true;
			var params = {
				ignore_empty_stories: false
			}
			if(vm.key)
				params = Object.assign(params, {q: vm.key});
			
			

			function onGetStoriesSuccess(data) {
				vm.searching= false;
				vm.search_key = angular.copy(vm.key);
				vm.results = angular.copy(data);
				j= vm.results.items.length;
			}

			function onGetStoriesError(err) {
				UtilService.onError(err);
				vm.searching = false;
			}

			storyDataService.getStoryPub(params, onGetStoriesSuccess, onGetStoriesError);
		}

		//watches
		$scope.$watch('exploreStoriesCtrl.filters', function(newValue, oldValue) {
			search(vm.form)
		}, true);

	}

	angular
		.module('todevise')
		.controller('exploreStoriesCtrl', controller);

}())