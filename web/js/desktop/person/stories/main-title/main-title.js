(function () {
	"use strict";

	function controller() {
		var vm = this;
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