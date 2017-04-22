(function () {
	"use strict";

	function controller() {
		var vm = this;
		vm.addComponent = addComponent;

		function addComponent(type) {
			vm.story.components.unshift({
				type: type,
				items: [],
				position: vm.story.components.length+1
			});
		}

	}

	var component = {
		templateUrl: currentHost() + "/js/desktop/person/stories/add-component/add-component.html",
		controller: controller,
		controllerAs: "storyAddComponentCtrl",
		bindings: {
			story: '<'
		}
	}

	angular
		.module('todevise')
		.component('storyAddComponent', component);

}());