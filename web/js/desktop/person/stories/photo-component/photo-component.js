(function () {
	"use strict";

	function controller() {
		var vm = this;
	}

	var component = {
		templateUrl: currentHost() + "/js/desktop/person/stories/photo-component/photo-component.html",
		controller: controller,
		controllerAs: "storyPhotoComponentCtrl",
		bindings: {
			story: '<'
		}
	}

	angular
		.module('todevise')
		.component('storyPhotoComponent', component);

}());