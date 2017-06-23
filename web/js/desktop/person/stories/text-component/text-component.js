(function () {
	"use strict";

	function controller(UtilService) {
		var vm = this;
		vm.stripHTMLTags = UtilService.stripHTMLTags;
		vm.parseText = parseText;
		vm.isLanguageOk = isLanguageOk;
		vm.completedLanguages = [];

		function parseText(text) {
			vm.completedLanguages = [];
			vm.languages.forEach(function(language) {
				if(angular.isObject(text) && text[language.code] && text[language.code] !== '') {
					vm.completedLanguages.push(language.code);
				}
			})
		}

		function isLanguageOk(code) {
			return vm.completedLanguages.indexOf(code) > -1 ? true : false;
		}

	}

	var component = {
		templateUrl: currentHost() + "/js/desktop/person/stories/text-component/text-component.html",
		controller: controller,
		controllerAs: "storyTextComponentCtrl",
		bindings: {
			component: '<',
			languages: '<'
		}
	}

	angular
		.module('person')
		.component('storyTextComponent', component);

}());