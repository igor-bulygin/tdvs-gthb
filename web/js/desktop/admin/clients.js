(function () {
	"use strict";

	function clientsCtrl($scope, $timeout, $person, toastr, $uibModal, $compile, $http) {
		var vm = this;

		vm.renderPartial = function () {
			$http.get(aus.syncToURL()).success(function (data, status) {
				angular.element('.body-content').html($compile(data)($scope));
			}).error(function (data, status) {
				toastr.error("Failed to refresh content!");
			});
		};

		vm.delete = function (client_id) {
			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/confirm.html',
				controller: 'confirmCtrl',
				resolve: {
					data: function () {
						return {
							title: "Are you sure?",
							text: "You are about to delete a client! All items related to this user (loved, boxes, settings, media....) will be deleted. This action can not be undone"
						};
					}
				}
			});

			modalInstance.result.then(function () {
				$person.get({
					short_id: client_id
				}).then(function (clients) {
					if (clients.length !== 1) return;
					var client = clients[0];

					$person.delete(client).then(function (data) {
						toastr.success("Influencer deleted!");
						vm.renderPartial();
					}, function (err) {
						toastr.error("Couldn't delete client!", err);
					});
				}, function (err) {
					toastr.error("Couldn't find client!", err);
				});
			}, function () {
				//Cancel
			});
		};

		vm.block = function (client_id) {
			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/confirm.html',
				controller: 'confirmCtrl',
				resolve: {
					data: function () {
						return {
							title: "Are you sure?",
							text: "You are about to block a client! The client will no longer have access to the web"
						};
					}
				}
			});

			modalInstance.result.then(function () {
				$person.get({
					short_id: client_id
				}).then(function (clients) {
					if (clients.length !== 1) return;
					var client = clients[0];
					client.account_state = 'blocked';

					$person.modify('POST', client).then(function (data) {
						toastr.success("client blocked!");
						vm.renderPartial();
					}, function (err) {
						toastr.error("Couldn't block client!", err);
					});
				}, function (err) {
					toastr.error("Couldn't find client!", err);
				});
			}, function () {
				//Cancel
			});
		};

		vm.draft = function (client_id) {
			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/confirm.html',
				controller: 'confirmCtrl',
				resolve: {
					data: function () {
						return {
							title: "Are you sure?",
							text: "You are about to set a client as active! The client will have access to the web"
						};
					}
				}
			});

			modalInstance.result.then(function () {
				$person.get({
					short_id: client_id
				}).then(function (clients) {
					if (clients.length !== 1) return;
					var client = clients[0];
					client.account_state = 'active';

					$person.modify('POST', client).then(function (data) {
						toastr.success("Client actived!");
						vm.renderPartial();
					}, function (err) {
						toastr.error("Couldn't activate client!", err);
					});
				}, function (err) {
					toastr.error("Couldn't find client!", err);
				});
			}, function () {
				//Cancel
			});
		};

		vm.change_email = function (client_id) {
			$person.get({
				short_id: client_id
			}).then(function (clients) {
				if (clients.length !== 1) {
					toastr.error("Unexpected client details!");
					return;
				}
				var client = clients[0];

				var modalInstance = $uibModal.open({
					templateUrl: 'template/modal/client/change_email.html',
					controller: clientChangeEmailCtrl,
					controllerAs: 'clientChangeEmailCtrl',
					resolve: {
						data: function () {
							return {
								email: client.credentials.email
							}
						}
					}
				});

				modalInstance.result.then(function (data) {
					client.change_email = data.email;

					$person.modify('POST', client).then(function (data) {
						toastr.success("Client email updated to "+client.change_email);
						vm.renderPartial();
					}, function (err) {
						toastr.error("Couldn't modify client!", err);
					});

				}, function () {
					//Cancel
				});
			});

		};
	}

	function clientChangeEmailCtrl($uibModalInstance, data) {
		var vm = this;

		vm.data = data;

		vm.ok = ok;
		vm.cancel = cancel;

		function ok() {
			$uibModalInstance.close({
				email: vm.data.email
			});
		};

		function cancel() {
			$uibModalInstance.dismiss();
		};
	}

	angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'angular-multi-select', 'global-admin', 'global-desktop', 'api'])
		.controller('clientsCtrl', clientsCtrl)

}());