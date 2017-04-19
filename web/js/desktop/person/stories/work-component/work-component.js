(function () {
	"use strict";

	function controller() {
		var vm = this;
	}

	var component = {
		templateUrl: currentHost() + "/js/desktop/person/stories/work-component/work-component.html",
		controller: controller,
		controllerAs: "storyWorkComponentCtrl",
		bindings: {
			story: '<'
		}
	}

	angular
		.module('todevise')
		.component('storyWorkComponent', component);
}());