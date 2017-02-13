(function () {
	"use strict";

	function controller($scope, lovedDataService, $uibModal) {
		var vm = this;
		vm.setLoved = setLoved;
		vm.productId = $scope.productId;
		
		function init() {
			vm.isLoved = $scope.isLoved ? true : false;
		}

		function setLoved() {
			if(!vm.isLoved) {
				var loved = new lovedDataService.LovedPriv;
				loved.product_id = vm.productId;
				loved.$save()
					.then(function (dataSaved) {
						vm.isLoved = true;
					}, function (err) {
						if(err.status === 401)
							openSignUpModal();
					});
			} else {
				lovedDataService.LovedPriv.delete({
					productId: vm.productId
				}).$promise.then(function (dataDeleted) {
					vm.isLoved = false;
				}, function (err) {
					if(err.status === 401)
						openSignUpModal();
				});
			}
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