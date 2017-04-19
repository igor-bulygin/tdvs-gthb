(function () {
	"use strict";

	function controller() {
		var vm = this;
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/person/stories/main-media/main-media.html',
		controller: controller,
		controllerAs: 'storyMainMediaCtrl',
		bindings: {
			story: '<',
		}
	}

	angular
		.module('todevise')
		.component('storyMainMedia', component);

}());