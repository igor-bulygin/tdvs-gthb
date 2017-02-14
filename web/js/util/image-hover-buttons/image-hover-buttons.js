(function () {
	"use strict";

	function controller($scope, lovedDataService, $uibModal) {
		var vm = this;
		vm.setLoved = setLoved;
		vm.productId = $scope.productId;

		function init() {
			vm.isLoved = $scope.isLoved == 1 ? true : false;
		}

		init();

		function setLoved() {
			if(!vm.isLoved) {
				lovedDataService.setLoved({
					product_id: vm.productId
				}, setLovedSuccess, setLovedError);
			} else {
				lovedDataService.deleteLoved({
						productId: vm.productId
					}, deleteLovedSuccess, setLovedError);
			}
		}

		function setLovedSuccess(data) {
			vm.isLoved = true;
		}

		function setLovedError(err) {
			if(err.status === 401)
				openSignUpModal();
		}

		function deleteLovedSuccess(data) {
			vm.isLoved = false;
		}

		function openSignUpModal() {
			var modalInstance = $uibModal.open({
				component: 'modalSignUpLoved',
				size: 'sm'
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