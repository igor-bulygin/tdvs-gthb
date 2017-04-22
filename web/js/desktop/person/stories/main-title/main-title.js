(function () {
	"use strict";

	function controller() {
		var vm = this;
		vm.parseTitle = parseTitle;
		vm.isLanguageOk = isLanguageOk;
		vm.completedLanguages = [];

		function parseTitle(title) {
			vm.completedLanguages = [];
			vm.languages.forEach(function(language) {
				if(angular.isObject(title) && title[language.code] && title[language.code] !== "") {
					vm.completedLanguages.push(language.code)
				}
			})
		}

		function isLanguageOk(code, title) {
			return vm.completedLanguages.indexOf(code) > -1 ? true : false;
		}
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/person/stories/main-title/main-title.html',
		controller: controller,
		controllerAs: 'storyMainTitleCtrl',
		bindings: {
			story: '<',
			languages: '<'
		}
	}

	angular
		.module('todevise')
		.component('storyMainTitle', component);

}());