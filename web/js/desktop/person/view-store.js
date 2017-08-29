(function () {
	"use strict";

	function controller(productDataService, UtilService, $uibModal, $window) {
		var vm = this;
		vm.open_modal_delete = open_modal_delete;

		init();

		function init() {

		}

		function open_modal_delete(id) {
			vm.id_to_delete = id;
			var modalInstance = $uibModal.open({
				templateUrl: 'modalDeleteProduct.html',
				controller: 'modalDeleteProductCtrl',
				controllerAs: 'modalDeleteProductCtrl'
			})

			modalInstance.result.then(function () {
				deleteProduct(vm.id_to_delete);
			});
		}

		function deleteProduct(id) {
			function onDeleteProductPrivSuccess(data) {
				$window.location.reload()
			}
			
			productDataService.deleteProductPriv({
				idProduct: id
			}, onDeleteProductPrivSuccess, UtilService.onError)
		}
	}

	function modalController($uibModalInstance) {
		var vm = this;
		vm.close = close;
		vm.ok = ok;

		function close() {
			$uibModalInstance.dismiss('cancel');
		}

		function ok() {
			$uibModalInstance.close();
		}
	}

	angular
		.module('person')
		.controller('modalDeleteProductCtrl', modalController)
		.controller('viewStoreCtrl', controller);

}());