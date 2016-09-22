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
				// invitation to a new Deviser
				data.code_invitation_type = 'invitation-deviser';
				invitationDataService.Invitation.save(data).$promise.then(function (data) {
					toastr.success("Invitations sent !");
					$window.location.reload();
				}, function (err) {
					toastr.error(err);
				});
			}, function () {
				//Cancel
			});
		}

		init();

	}

	function create_newCtrl($uibModalInstance, data) {
		var vm = this;

		vm.data = data;

		vm.ok = function () {
			$uibModalInstance.close({
				email: vm.email,
				first_name: vm.first_name
			});
		};

		vm.cancel = function () {
			$uibModalInstance.dismiss();
		};
	}

	angular.module('todevise', ['api', 'ui.bootstrap', 'toastr'])
		.controller('editInvitationCtrl', controller);

}());