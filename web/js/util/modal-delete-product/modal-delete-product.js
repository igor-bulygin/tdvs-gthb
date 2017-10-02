(function () {
	"use strict";

	function controller($uibModal, productDataService) {
		var vm = this;
		vm.close = close;
		vm.ok = ok;

		function close() {
			vm.modalInstance.dismiss('cancel');
		}

		function ok() {
			function onDeleteProductPrivSuccess(data) {
				vm.modalInstance.close()
;			}

			function onDeleteError(err) {
				if(err.status===405) {
					vm.cannot_delete_error = true;
				}
			}

			productDataService.deleteProductPriv({
				idProduct: vm.resolve.productId
			}, onDeleteProductPrivSuccess, onDeleteError);

		}
	}

	var component = {
		templateUrl: currentHost() + "/js/util/modal-delete-product/modal-delete-product.html",
		controller: controller,
		controllerAs: "modalDeleteProductCtrl",
		bindings: {
			resolve: '<',
			modalInstance: '<'
		}
	}

	angular
		.module('util')
		.component('modalDeleteProduct', component);

}());