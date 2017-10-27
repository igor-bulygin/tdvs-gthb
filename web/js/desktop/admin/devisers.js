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
				}).then(function (devisers) {
					if (devisers.length !== 1) return;
					var deviser = devisers[0];

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

		vm.fee = function (deviser_id) {
			$person.get({
				short_id: deviser_id
			}).then(function (devisers) {
				if (devisers.length !== 1) {
					toastr.error("Unexpected deviser details!");
					return;
				}
				var deviser = devisers[0];

				var modalInstance = $uibModal.open({
					templateUrl: 'template/modal/deviser/fee.html',
					controller: deviserFeeCtrl,
					controllerAs: 'deviserFeeCtrl',
					resolve: {
						data: function () {
							return {
								application_fee: parseFloat(deviser.application_fee)
							}
						}
					}
				});

				modalInstance.result.then(function (data) {
					if (isNaN(parseFloat(data.application_fee))) {
						toastr.error("You must specify a number. Example: set 0.145 for 14.5%, or leave empty to use default setting");
						return;
					}
					deviser.application_fee = data.application_fee;

					$person.modify('POST', deviser).then(function (data) {
						if (deviser.application_fee) {
							toastr.success("Deviser fee set to "+parseFloat(deviser.application_fee*100).toFixed(2)+" %");
						} else {
							toastr.success("Deviser fee set to default");
						}
						vm.renderPartial();
					}, function (err) {
						toastr.error("Couldn't modify deviser!", err);
					});

				}, function () {
					//Cancel
				});
			});

		};
	}

	function deviserFeeCtrl($uibModalInstance, data) {
		var vm = this;

		vm.data = data;

		vm.ok = ok;
		vm.cancel = cancel;

		function ok() {
			$uibModalInstance.close({
				application_fee: vm.data.application_fee
			});
		};

		function cancel() {
			$uibModalInstance.dismiss();
		};
	}

	angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'angular-multi-select', 'global-admin', 'global-desktop', 'api'])
		.controller('devisersCtrl', devisersCtrl)
		.controller('deviserFeeCtrl', deviserFeeCtrl)

}());