(function () {
	"use strict";

	function adminsCtrl($scope, $timeout, $admin, $admin_util, toastr, $uibModal, $compile, $http) {
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
					toastr.error("Couldn't find deviser!", err);
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
				$admin.modify("POST", admin).then(function (deviser) {
					toastr.success("Admin created!");
					vm.renderPartial();
				}, function (err) {
					toastr.error("Couldn't create admin!", err);
				});
			}, function () {
				//Cancel
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

	angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'global-admin', 'global-desktop', 'api'])
		.controller('adminsCtrl', adminsCtrl);

}());