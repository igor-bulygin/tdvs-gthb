(function () {
	"use strict";

	function controller() {
		var vm = this;
	}

	var component = {
		templateUrl: currentHost() + "/js/desktop/person/stories/video-component/video-component.html",
		controller: controller,
		controllerAs: "storyVideoComponentCtrl",
		bindings: {
			component: '<'
		}
	}

	angular
		.module('todevise')
		.component('storyVideoComponent', component);

}());