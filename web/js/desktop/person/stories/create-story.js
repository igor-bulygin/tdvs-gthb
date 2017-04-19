(function () {
	"use strict";

	function controller(languageDataService, UtilService) {
		var vm = this;
		vm.story = {};

		init();

		function init() {
			getLanguages();
		}

		function getLanguages() {
			function onGetLanguagesSuccess(data) {
				vm.languages = angular.copy(data.items);
			}

			languageDataService.getLanguages(onGetLanguagesSuccess, UtilService.onError);
		}
	}

	angular
		.module('todevise')
		.controller('createStoryCtrl', controller);

}());