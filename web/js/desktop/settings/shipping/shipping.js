(function () {
	"use strict";

	function controller(personDataService, languageDataService, UtilService) {
		var vm = this;
		vm.person = angular.copy(person);

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
		.controller('shippingSettingsCtrl', controller);
}());