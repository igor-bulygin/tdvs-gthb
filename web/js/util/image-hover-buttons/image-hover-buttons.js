(function () {
	"use strict";

	function controller($scope, lovedDataService, boxDataService, $uibModal) {
		var vm = this;
		vm.setLoved = setLoved;
		vm.setBoxes = setBoxes;
		vm.productId = $scope.productId;

		function init() {
			vm.isLoved = $scope.isLoved == 1 ? true : false;
		}

		init();

		function setLoved() {
			function setLovedSuccess(data) {
				vm.isLoved = true;
			}

			function setLovedError(err) {
				if(err.status === 401)
					openSignUpModal('loved');
			}
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

		function deleteLovedSuccess(data) {
			vm.isLoved = false;
		}

		function setBoxes() {
			function onGetBoxSuccess(data) {
				var modalInstance = $uibModal.open({
					component: 'modalSaveBox',
					size: 'sm',
					resolve: {
						productId: function() {
							return vm.productId;
						}
					}
				});
			}

			function onGetBoxError(err) {
				if(err.status === 401 || err.status === 404)
					openSignUpModal('boxes');
			}

			boxDataService.getBoxPriv(null, onGetBoxSuccess, onGetBoxError);
		}

		function openSignUpModal(component) {
			var modalInstance = $uibModal.open({
				component: 'modalSignUpLoved',
				size: 'sm',
				resolve: {
					icon: function () {
						return component;
					}
				}
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