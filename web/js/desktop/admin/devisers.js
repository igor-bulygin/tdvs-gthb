(function () {
	"use strict";

	function devisersCtrl($scope, $timeout, $person, toastr, $uibModal, $compile, $http) {
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
							text: "You are about to delete a deviser! All items related to this user (products, loved, boxes, settings, media....) will be deleted. This action can not be undone"
						};
					}
				}
			});

			modalInstance.result.then(function () {
				$person.get({
					short_id: deviser_id
				}).then(function (deviser) {
					if (deviser.length !== 1) return;
					deviser = deviser.shift();

					$person.delete(deviser).then(function (data) {
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
	}

	angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'angular-multi-select', 'global-admin', 'global-desktop', 'api'])
		.controller('devisersCtrl', devisersCtrl)

}());