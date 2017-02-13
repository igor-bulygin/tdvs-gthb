(function () {
	"use strict";

	function controller() {
		var vm = this;
		console.log("directive!!");
	}

	function directive() {
		return {
			restrict: 'A',
			templateUrl: currentHost() + '/js/util/image-hover-buttons/image-hover-buttons.html',
			controller: controller,
			controllerAs: 'imageHoverButtonsCtrl',
			transclude: true
		}
	}

	angular.module('util')
		.directive('imageHoverButtons', directive);

}());