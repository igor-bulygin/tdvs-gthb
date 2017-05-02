(function () {
	"use strict";

	function controller(languageDataService, UtilService, storyDataService, personDataService, productDataService,
		$window, dragndropService) {
		var vm = this;
		vm.save = save;
		vm.dragStart = dragStart;
		vm.dragOver = dragOver;
		vm.moved = moved;
		vm.canceled = canceled;
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
				//console.log(data);
				$window.location.href = currentHost() + data.view_link;
			}

			storyDataService.createStory(story, onCreateStorySuccess, UtilService.onError);
		}

		function dragStart(index) {
			dragndropService.dragStart(index, vm.story.components);
		}

		function dragOver(index) {
			vm.story.components = dragndropService.dragOver(index, vm.story.components);
			return true;
		}

		function moved(index) {
			vm.story.components = dragndropService.moved(index);
			parseComponentsPosition();
		}

		function canceled() {
			vm.story.components = dragndropService.canceled();
		}

		function parseComponentsPosition() {
			vm.story.components = vm.story.components.map(function(element, index) {
				element.position = index+1;
				return element;
			})
		}
	}

	angular
		.module('todevise')
		.controller('createStoryCtrl', controller);

}());