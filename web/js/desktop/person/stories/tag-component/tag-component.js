(function () {
	"use strict";

	function controller() {
		var vm = this;
		vm.addTag = addTag;
		vm.removeTag = removeTag;
		vm.tags = {};
		
		function addTag(tag) {
			if(!vm.story.tags[vm.languageSelected])
				vm.story.tags[vm.languageSelected] = [tag.text];
			else {
				if(vm.story.tags[vm.languageSelected].indexOf(tag.text) === -1)
					vm.story.tags[vm.languageSelected].push(tag.text)
			}
		}

		function removeTag(tag) {
			var pos = vm.product.tags[vm.languageSelected].indexOf(tag.text);
			if(pos > -1)
				vm.story.tags[vm.languageSelected].splice(pos, 1);
			if(vm.story.tags[vm.languageSelected].length === 0)
				delete vm.story.tags[vm.languageSelected];
		}
	}

	var component = {
		templateUrl: currentHost() + "/js/desktop/person/stories/tag-component/tag-component.html",
		controller: controller,
		controllerAs: "storyTagComponentCtrl",
		bindings: {
			story: '<',
			languages: '<'
		}
	}

	angular
		.module('todevise')
		.component('storyTagComponent', component);

}());