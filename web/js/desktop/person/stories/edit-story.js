(function () {
	"use strict";

	function controller(storyDataService, languageDataService, UtilService, personDataService, productDataService, 
		$window, dragndropService) {
		var vm = this;
		vm.story = angular.copy(story);
		vm.save = save;

		init();

		function init() {
			getLanguages();
			getDevisers();
			getCategories()
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
			var params = {
				idStory: vm.story.id
			}

			function onUpdateStorySuccess(data) {
				$window.location.href = currentHost() + data.view_link;
			}

			storyDataService.updateStory(vm.story, params, onUpdateStorySuccess, UtilService.onError);
		}
	}

	angular
		.module('todevise')
		.controller('editStoryCtrl', controller);

}());