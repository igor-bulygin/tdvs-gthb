(function () {
	"use strict";

	function controller($scope) {
		var vm = this;
		vm.setLoved = setLoved;
		console.log($scope.productId);

		function setLoved() {
			//call to API with $scope.productId
		}
		
	}

	function directive() {
		return {
			restrict: 'E',
			templateUrl: currentHost() + '/js/util/image-hover-buttons/image-hover-buttons.html',
			controller: controller,
			controllerAs: 'imageHoverButtonsCtrl',
			transclude: true,
			scope: {
				productId: '@'
			}
		}
	}

	angular.module('util')
		.directive('imageHoverButtons', directive);

}());