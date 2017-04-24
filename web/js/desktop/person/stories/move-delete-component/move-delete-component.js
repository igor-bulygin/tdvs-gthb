(function () {
	"use strict";

	function controller($scope) {
		var vm = this;
		vm.removeComponent = removeComponent;

		function removeComponent() {
			$scope.story.components.splice($scope.position, 1);
		}
	}

	function directive() {
		return {
			restrict: 'E',
			templateUrl: currentHost() + '/js/desktop/person/stories/move-delete-component/move-delete-component.html',
			controller: controller,
			controllerAs: 'moveDeleteComponentCtrl',
			transclude: true,
			scope: {
				story: '=',
				position: '='
			}
		}
	}

angular
	.module('todevise')
	.directive('moveDeleteComponent', directive);

}());