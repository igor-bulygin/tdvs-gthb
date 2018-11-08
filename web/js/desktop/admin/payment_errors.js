(function () {
	"use strict";

	function paymentErrorsCtrl($scope, $timeout, $payment_error, toastr, $uibModal, $compile, $http) {
		var vm = this;

		vm.renderPartial = function () {
			$http.get(aus.syncToURL()).success(function (data, status) {
				angular.element('.body-content').html($compile(data)($scope));
			}).error(function (data, status) {
				toastr.error("Failed to refresh content!");
			});
		};

		vm.transfer = function (short_id) {
			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/confirm.html',
				controller: 'confirmCtrl',
				resolve: {
					data: function () {
						return {
							title: "Are you sure?",
							text: "You are about to transfer money to this person! If the transfer goes well, the error will be deleted."
						};
					}
				}
			});

			modalInstance.result.then(function () {
				$payment_error.get({
					short_id: short_id
				}).then(function (payment_errors) {
					if (payment_errors.length !== 1) return;
					var payment_error = payment_errors[0];
          console.log(payment_error);

          $payment_error.delete(payment_error).then(function (data) {
						toastr.success("Payment Error solved and deleted!");
						vm.renderPartial();
					}, function (err) {
						toastr.error("Couldn't solve Payment Error!", err);
					});

					//$person.delete(client).then(function (data) {
					// 	toastr.success("Influencer deleted!");
					// 	vm.renderPartial();
					// }, function (err) {
					// 	toastr.error("Couldn't delete client!", err);
					// });
				}, function (err) {
					toastr.error("Couldn't find Payment Error!", err);
				});
			}, function () {
				//Cancel
			});
		};

	}

	angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'angular-multi-select', 'global-admin', 'global-desktop', 'api'])
		.controller('paymentErrorsCtrl', paymentErrorsCtrl)

}());
