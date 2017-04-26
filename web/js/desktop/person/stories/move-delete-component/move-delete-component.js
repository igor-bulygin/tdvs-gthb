(function () {
	"use strict";

	function controller($scope) {
		var vm = this;
		vm.removeItem = removeItem;

		function removeItem() {
			$scope.array.splice($scope.position, 1);
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
				array: '=',
				position: '='
			}
		}
	}

angular
	.module('todevise')
	.directive('moveDeleteComponent', directive);

}());