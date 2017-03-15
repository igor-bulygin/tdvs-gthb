(function () {
	"use strict";

	function controller($scope, invitationDataService, toastr, $uibModal, $window) {
		var vm = this;

		vm.create_new = create_new;
		vm.send = send;
		vm.view = view;
		vm.delete = deleteInvitation;

		function init() {
			//
		}

		function send() {
			toastr.info("Not implemented yet");
		}

		function view(emailId) {
			$window.open('/postman/emails/' + emailId, "_blank") ;
		}

		function deleteInvitation() {
			toastr.info("Not implemented yet");
		}

		function create_new() {
			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/tag/create_new.html',
				controller: create_newCtrl,
				controllerAs: 'create_newCtrl',
				resolve: {
					data: function () {
						return {};
					}
				}
			});

			modalInstance.result.then(function (data) {
				//ok
				$window.location.reload();
			}, function () {
				//Cancel
			});
		}

		init();

	}

	function create_newCtrl($uibModalInstance, data, invitationDataService) {
		var vm = this;
		vm.data = data;
		vm.createInvitation = createInvitation;

		function createInvitation(form) {
			function onCreateInvitationSuccess(data) {
				$uibModalInstance.close();
			}

			function onCreateInvitationError(err) {
				if(err.status === 409)
					vm.error_messages = "This account already exists."
			}

			form.$setSubmitted();
			if(form.$valid) {
				invitationDataService.createInvitationAdmin({
					email: vm.email,
					first_name: vm.first_name,
					no_email: vm.no_email,
					code_invitation_type: vm.code_invitation_type
				}, null, onCreateInvitationSuccess, onCreateInvitationError);
			}

		}

		vm.cancel = function () {
			$uibModalInstance.dismiss();
		};
	}

	angular.module('todevise', ['api', 'ui.bootstrap', 'toastr'])
		.controller('editInvitationCtrl', controller);

}());