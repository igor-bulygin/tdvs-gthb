(function () {
	"use strict";

	function adminsCtrl($scope, $timeout, $admin, $person, $admin_util, toastr, $uibModal, $compile, $http) {
		var vm = this;

		vm.renderPartial = function () {
			$http.get(aus.syncToURL()).success(function (data, status) {
				angular.element('.body-content').html($compile(data)($scope));
			}).error(function (data, status) {
				toastr.error("Failed to refresh content!");
			});
		};

		vm.delete = function (admin_id) {
			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/confirm.html',
				controller: 'confirmCtrl',
				resolve: {
					data: function () {
						return {
							title: "Are you sure?",
							text: "You are about to delete an admin!"
						};
					}
				}
			});

			modalInstance.result.then(function () {
				$admin.get({
					short_id: admin_id
				}).then(function (admin) {
					if (admin.length !== 1) return;
					admin = admin.shift();

					$admin.delete(admin).then(function (data) {
						toastr.success("Admin deleted!");
						vm.renderPartial();
					}, function (err) {
						toastr.error("Couldn't delete tag!", err);
					});
				}, function (err) {
					toastr.error("Couldn't find admin!", err);
				});
			}, function () {
				//Cancel
			});
		};

		vm.create_new = function () {
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
				var admin = $admin_util.newAdmin(data.type, data.name, data.surnames, data.email, data.password);
				$admin.modify("POST", admin).then(function (admin) {
					toastr.success("Admin created!");
					vm.renderPartial();
				}, function (err) {
					toastr.error("Couldn't create admin!", err);
				});
			}, function () {
				//Cancel
			});
		};

		vm.change_email = function (admin_id) {
			$person.get({
				short_id: admin_id
			}).then(function (admins) {
				if (admins.length !== 1) {
					toastr.error("Unexpected admin details!");
					return;
				}
				var admin = admins[0];

				var modalInstance = $uibModal.open({
					templateUrl: 'template/modal/admin/change_email.html',
					controller: adminChangeEmailCtrl,
					controllerAs: 'adminChangeEmailCtrl',
					resolve: {
						data: function () {
							return {
								email: admin.credentials.email
							}
						}
					}
				});

				modalInstance.result.then(function (data) {
					admin.change_email = data.email;

					$person.modify('POST', admin).then(function (data) {
						toastr.success("admin email updated to "+admin.change_email);
						vm.renderPartial();
					}, function (err) {
						toastr.error("Couldn't modify admin!", err);
					});

				}, function () {
					//Cancel
				});
			});

		};

	}

	function create_newCtrl($uibModalInstance, data) {
		var vm = this;

		vm.data = data;
		vm.surnames = [];

		vm.ok = function () {
			var _surnames = vm.surnames.map(function (v) {
				return v.value;
			});

			$uibModalInstance.close({
				type: [_ADMIN],
				name: vm.name,
				surnames: _surnames,
				email: vm.email,
				password: vm.password
			});
		};

		vm.cancel = function () {
			$uibModalInstance.dismiss();
		};
	}

	function adminChangeEmailCtrl($uibModalInstance, data) {
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

	angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'global-admin', 'global-desktop', 'api'])
		.controller('adminsCtrl', adminsCtrl);

}());