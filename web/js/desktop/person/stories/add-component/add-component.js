(function () {
	"use strict";

	function controller() {
		var vm = this;
		vm.addComponent = addComponent;

		function addComponent(type) {
			var items;
			if(type === 1)
				items = {};
			else {
				items = [];
			}
			vm.story.components.push({
				type: type,
				items: items,
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
		.module('person')
		.component('storyAddComponent', component);

}());