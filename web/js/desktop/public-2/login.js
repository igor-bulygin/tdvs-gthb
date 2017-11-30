(function () {
	"use strict";

	function controller(personDataService, $window, UtilService,localStorageUtilService) {
		var vm = this;
		vm.has_error = UtilService.has_error;
		vm.login = login;
		vm.loading=false;
		vm.resetPasswordEmail="";
		vm.askForResetPassword = askForResetPassword;

		function login() {
			vm.loading=true;
			function onLoginSuccess(data) {
				localStorageUtilService.setLocalStorage('access_token', data.access_token);
				$window.location.href = data.return_url;
			}

			function onLoginError(err) {
				UtilService.onError(err);
				vm.errors = true;
				vm.loading=false;
			}

			personDataService.login(vm.user, null, onLoginSuccess, onLoginError);
		}

		function askForResetPassword() {
			vm.resetPasswordEmailRequired=false;
			vm.passwordEmailWrongFormat=false;
			if (vm.resetPasswordEmail && vm.resetPasswordEmail.length>0 && vm.forgotenPasswordForm.$valid) {
				vm.forgotPasswordSended = false;
				vm.loading=true;
				function onAskForResetPasswordSuccess(data) {
					vm.forgotPasswordSended = true;
					vm.loading=false;
				}
				function onAskForResetPasswordError(err) {
					UtilService.onError(err);
					vm.loading=false;
				}
				personDataService.askForResetPassword({email: vm.resetPasswordEmail}, onAskForResetPasswordSuccess, onAskForResetPasswordError);
			}
			else if (vm.resetPasswordEmail && !vm.resetPasswordEmail.length>0) {
				vm.resetPasswordEmailRequired=true;
			}
			else {
				vm.passwordEmailWrongFormat=true;
			}
		}
	}

	angular.module('todevise')
	.controller('loginCtrl', controller);

}());