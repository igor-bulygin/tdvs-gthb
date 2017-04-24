(function () {
	"use strict";

	function controller() {
		var vm = this;
	}

	function directive() {
		return {
			restrict: 'E',
			templateUrl: currentHost() + '/js/desktop/person/stories/move-delete-component/move-delete-component.html',
			controller: controller,
			controllerAs: 'moveDeleteComponentCtrl',
			transclude: true,
			scope: {
				story: '='
			}
		}
	}

angular
	.module('todevise')
	.directive('moveDeleteComponent', directive);

}());