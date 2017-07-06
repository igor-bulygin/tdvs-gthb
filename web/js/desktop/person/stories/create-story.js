(function () {
	"use strict";

	function controller(languageDataService, UtilService, storyDataService, personDataService, productDataService,
		$window, dragndropService) {
		var vm = this;
		vm.save = save;
		init();

		function init() {
			vm.story = newStory();
			getLanguages();
			getDevisers();
			getCategories();
		}

		function newStory() {
			var story = {
				title: {},
				components: [],
				categories: [],
				tags: {},
				person_id: person.short_id,
				story_state: 'story_state_active'
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

		function getCategories() {
			function onGetCategoriesSuccess(data) {
				vm.categories = angular.copy(data.items);
			}

			productDataService.getCategories(null, onGetCategoriesSuccess, UtilService.onError);
		}

		function save(story) {
			function onCreateStorySuccess(data) {
				$window.location.href = currentHost() + data.view_link;
			}

			storyDataService.createStory(story, onCreateStorySuccess, UtilService.onError);
		}
	}

	angular
		.module('person')
		.controller('createStoryCtrl', controller);

}());