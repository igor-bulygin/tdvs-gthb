(function () {
	"use strict";

	function controller(boxDataService, $uibModal, UtilService, $window) {
		var vm = this;
		vm.box = angular.copy(box);
		vm.openEditBoxModal = openEditBoxModal;
		vm.openDeleteBoxModal = openDeleteBoxModal;
		vm.deleteProduct = deleteProduct;

		function openDeleteBoxModal() {
			var modalInstance = $uibModal.open({
				component: 'modalDeleteBox',
				size: 'sm',
				resolve: {
					boxId: function() {
						return vm.box.id
					},
					deviser: function() {
						return vm.box.person
					}
				}
			});
		}

		function openEditBoxModal() {
			var modalInstance = $uibModal.open({
				component: 'modalEditBox',
				size: 'sm',
				resolve: {
					box: function() {
						return vm.box
					}
				}
			});

			modalInstance.result.then(function (returnData) {
				if(angular.isObject(returnData)) {
					vm.box = angular.copy(returnData);
				}
			}, function (err) {
				//errors
			});
		}

		function deleteProduct(productId) {
			function onDeleteProductSuccess(data) {
				$window.location.reload();
			}

			boxDataService.deleteProduct({
				idBox: vm.box.id,
				idProduct: productId
			}, onDeleteProductSuccess, UtilService.onError);
		}

	}

	angular.module('box')
		.controller('boxDetailCtrl', controller);

}());