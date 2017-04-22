(function () {
	"use strict";

	function controller() {
		var vm = this;
	}

	var component = {
		templateUrl: currentHost() + "/js/desktop/person/stories/text-component/text-component.html",
		controller: controller,
		controllerAs: "storyTextComponentCtrl",
		bindings: {
			component: '<'
		}
	}

	angular
		.module('todevise')
		.component('storyTextComponent', component);

}());