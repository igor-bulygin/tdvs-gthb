(function () {
	"use strict";

	function controller($scope, lovedDataService) {
		var vm = this;
		vm.setLoved = setLoved;
		vm.productId = $scope.productId;
		
		function init() {
			vm.isLoved = $scope.isLoved ? true : false;
		}

		function setLoved() {
			var loved = new lovedDataService.LovedPriv;
			loved.product_id = vm.productId;
			loved.$save()
				.then(function (dataSaved) {
					vm.isLoved = true;
				}, function (err) {
					//ToDo: show errors?
				});
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
				productId: '@',
				isLoved: '@'
			}
		}
	}

	angular.module('util')
		.directive('imageHoverButtons', directive);

}());