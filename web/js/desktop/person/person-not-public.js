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
			});
		}

	}

	angular
		.module('person')
		.controller('personNotPublicCtrl', controller)

}());