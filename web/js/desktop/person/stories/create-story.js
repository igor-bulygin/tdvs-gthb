(function () {
	"use strict";

	function controller(languageDataService, UtilService, storyDataService, personDataService, $window) {
		var vm = this;
		vm.save = save;

		init();

		function init() {
			vm.story = newStory();
			getLanguages();
			getDevisers();
		}

		function newStory() {
			var story = {
				title: {},
				components: []
			};
			return story;
		}

		function getLanguages() {
			function onGetLanguagesSuccess(data) {
				vm.languages = angular.copy(data.items);
			}

			languageDataService.getLanguages(onGetLanguagesSuccess, UtilService.onError);
		}

		function getDevisers() {
			var params = {
				type: 2
			}
			function onGetDevisersSuccess(data) {
				vm.devisers = angular.copy(data.items);
			}

			personDataService.getPeople(params, onGetDevisersSuccess, UtilService.onError);
		}

		function save(story) {
			function onCreateStorySuccess(data) {
				//console.log(data);
				$window.location.href = currentHost() + data.view_link;
			}

			storyDataService.createStory(story, onCreateStorySuccess, UtilService.onError);
		}
	}

	angular
		.module('todevise')
		.controller('createStoryCtrl', controller);

}());