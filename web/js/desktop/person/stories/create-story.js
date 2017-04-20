(function () {
	"use strict";

	function controller(languageDataService, UtilService) {
		var vm = this;

		init();

		function init() {
			vm.story = newStory();
			getLanguages();
		}

		function newStory() {
			var story = {};
			story['title'] = {
				'en-US': 'Main title'
			}
			return story;
		}

		function getLanguages() {
			function onGetLanguagesSuccess(data) {
				vm.languages = angular.copy(data.items);
			}

			languageDataService.getLanguages(onGetLanguagesSuccess, UtilService.onError);
		}
	}

	angular
		.module('todevise', ['api', 'util', 'header', 'xeditable', 'nya.bootstrap.select'])
		.controller('createStoryCtrl', controller);

}());