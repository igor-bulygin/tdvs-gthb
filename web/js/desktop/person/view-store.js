(function () {
	"use strict";

	function controller(productDataService, UtilService, $uibModal, $window) {
		var vm = this;
		vm.open_modal_delete = open_modal_delete;

		init();

		function init() {

		}

		function open_modal_delete(id) {
			var modalInstance = $uibModal.open({
				component: 'modalDeleteProduct',
				resolve: {
					productId: function() {
						return id;
					}
				}
			});

			modalInstance.result.then(function(data) {
				$window.location.reload();
			})
		}

	}

	angular
		.module('person')
		.controller('viewStoreCtrl', controller);

}());