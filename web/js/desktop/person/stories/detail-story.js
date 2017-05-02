(function () {
	"use strict";

	function controller(storyDataService, UtilService, $window) {
		var vm = this;
		vm.deleteStory = deleteStory;
		vm.story = angular.copy(story);
		vm.person = angular.copy(person);

		function deleteStory(id) {
			var params = {
				idStory: id
			}

			function onDeleteStorySuccess(data) {
				$window.location.href = vm.person.stories_link;
			}

			storyDataService.deleteStory(params, onDeleteStorySuccess, UtilService.onError);
		}
	}

	angular
		.module('todevise')
		.controller('detailStoryCtrl', controller);

}());