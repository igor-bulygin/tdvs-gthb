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

		vm.block = function (deviser_id) {
			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/confirm.html',
				controller: 'confirmCtrl',
				resolve: {
					data: function () {
						return {
							title: "Are you sure?",
							text: "You are about to block a deviser! The deviser will no longer have access to the web"
						};
					}
				}
			});

			modalInstance.result.then(function () {
				$person.get({
					short_id: deviser_id
				}).then(function (devisers) {
					if (devisers.length !== 1) return;
					var deviser = devisers[0];
					deviser.account_state = 'blocked';
					console.log(deviser);

					$person.modify('POST', deviser).then(function (data) {
						toastr.success("Deviser blocked!");
						vm.renderPartial();
					}, function (err) {
						toastr.error("Couldn't block deviser!", err);
					});
				}, function (err) {
					toastr.error("Couldn't find deviser!", err);
				});
			}, function () {
				//Cancel
			});
		};

		vm.draft = function (deviser_id) {
			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/confirm.html',
				controller: 'confirmCtrl',
				resolve: {
					data: function () {
						return {
							title: "Are you sure?",
							text: "You are about to set a deviser as draft! The deviser will have access to the web, but his profile will be marked as draft"
						};
					}
				}
			});

			modalInstance.result.then(function () {
				$person.get({
					short_id: deviser_id
				}).then(function (devisers) {
					if (devisers.length !== 1) return;
					var deviser = devisers[0];
					deviser.account_state = 'draft';
					console.log(deviser);

					$person.modify('POST', deviser).then(function (data) {
						toastr.success("Deviser actived as draft!");
						vm.renderPartial();
					}, function (err) {
						toastr.error("Couldn't activate deviser!", err);
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