(function () {
	"use strict";

	function controller($window, personDataService, productDataService, UtilService, $uibModal) {
		var vm = this;
		vm.person = person;
		vm.makeProfilePublic = makeProfilePublic;
		vm.open_modal_delete = open_modal_delete;

		function makeProfilePublic() {
			function onUpdateProfileSuccess(returnData) {
				if(returnData.main_link)
					$window.location.href = returnData.main_link;
			}

			var data = {
				account_state: "active"
			}

			personDataService.updateProfile(data, {
				personId: vm.person.short_id
			}, onUpdateProfileSuccess, UtilService.onError);
		}

		function deleteProduct(id) {
			function onDeleteProductPrivSuccess(data) {
				$window.location.reload();
			}
			
			productDataService.deleteProductPriv({
				idProduct: id
			}, onDeleteProductPrivSuccess, UtilService.onError)
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
		.module('todevise')
		.controller('personNotPublicCtrl', controller)
		.controller('modalDeleteProductCtrl', modalController);

}());