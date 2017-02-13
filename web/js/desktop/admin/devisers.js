(function () {
	"use strict";

	function devisersCtrl($scope, $timeout, $deviser, $deviser_util, toastr, $uibModal, $compile, $http) {
		var vm = this;

		vm.renderPartial = function () {
			$http.get(aus.syncToURL()).success(function (data, status) {
				angular.element('.body-content').html($compile(data)($scope));
			}).error(function (data, status) {
				toastr.error("Failed to refresh content!");
			});
		};

		vm.delete = function (deviser_id) {
			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/confirm.html',
				controller: 'confirmCtrl',
				resolve: {
					data: function () {
						return {
							title: "Are you sure?",
							text: "You are about to delete a deviser! All items related to this user (products, settings, media....) will be deleted. This action can not be undone"
						};
					}
				}
			});

			modalInstance.result.then(function () {
				$deviser.get({
					short_id: deviser_id
				}).then(function (deviser) {
					if (deviser.length !== 1) return;
					deviser = deviser.shift();

					$deviser.delete(deviser).then(function (data) {
						toastr.success("Deviser deleted!");
						vm.renderPartial();
					}, function (err) {
						toastr.error("Couldn't delete deviser!", err);
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
				var deviser = $deviser_util.newDeviser(data.type, data.name, data.surnames, data.email, data.country, data.slug);
				$deviser.modify("POST", deviser).then(function (deviser) {
					toastr.success("Deviser created!");
					vm.renderPartial();
				}, function (err) {
					toastr.error("Couldn't create tag!", err);
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
		vm.selected_country = {};

		vm.ok = function () {
			var _surnames = vm.surnames.map(function (v) {
				return v.value;
			});
			var _country = vm.selected_country.pop();

			$uibModalInstance.close({
				type: [_DEVISER],
				name: vm.name,
				surnames: _surnames,
				email: vm.email,
				country: _country.country_code,
				slug: vm.slug
			});
		};

		vm.cancel = function () {
			$uibModalInstance.dismiss();
		}
	}

	angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'angular-multi-select', 'global-admin', 'global-desktop', 'api'])
		.controller('devisersCtrl', devisersCtrl)

}());