(function () {
	"use strict";

	function controller() {
		var vm = this;
		vm.isCategorySelected = isCategorySelected;

		function isCategorySelected(id) {
			if(angular.isArray(vm.story.categories) && vm.story.categories.length > 0)
				return vm.story.categories.indexOf(id) > -1 ? true : false;
			else {
				return false;
			}
		}
	}

	var component = {
		templateUrl: currentHost() + "/js/desktop/person/stories/category-component/category-component.html",
		controller: controller,
		controllerAs: "storyCategoryComponentCtrl",
		bindings: {
			story: '<',
			categories: '<'
		}
	}

	angular
		.module('todevise')
		.component('storyCategoryComponent', component);

}());