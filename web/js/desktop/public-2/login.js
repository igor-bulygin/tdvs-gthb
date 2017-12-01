(function () {
	"use strict";

	function controller(personDataService, $window, UtilService,localStorageUtilService) {
		var vm = this;
		vm.has_error = UtilService.has_error;
		vm.login = login;
		vm.loading=false;
		vm.resetPasswordEmail="";
		vm.askForResetPassword = askForResetPassword;
		vm.resetPassword = resetPassword;

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

		function resetPassword() {
			vm.resetEmail = resetEmail;
			vm.person_id = person_id;
			vm.action_id = action_id;
			vm.newPasswordSended = false;
			vm.newPasswordRequired=false;
			vm.newRepeatedPasswordRequired=false;
			vm.distinctPasswords=false;
			if (!vm.newPassword || !vm.newPassword.length>0) {
				vm.newPasswordRequired=true;
			}
			else if (!vm.newRepeatedPassword || !vm.newRepeatedPassword.length>0) {
				vm.newRepeatedPasswordRequired=true;
			}
			else if (!(vm.newPasswordRequired === vm.newRepeatedPasswordRequired)) {
				vm.distinctPasswords=true;
			}
			else {
				vm.loading=true;
				function onResetPasswordSuccess(data) {
					// vm.newPasswordSended = true;
					// vm.loading = false;

					function onLoginSuccess(data) {
						if (data.access_token) {
							localStorageUtilService.setLocalStorage('access_token', data.access_token);
						}
						if (data.return_url)
							$window.location.href = data.return_url;
					}

					function onLoginError(err) {
						vm.loading = false;
						console.log(err);
					}

					var loginData = {
						email: vm.resetEmail,
						password: vm.newPassword
					};
					personDataService.login(loginData, null, onLoginSuccess, onLoginError);
				}

				function onResetPasswordError(err) {
					UtilService.onError(err);
					vm.loading=false;
				}

				var data = {
					email: vm.resetEmail,
					new_password: vm.newPassword,
					repeat_password: vm.newRepeatedPassword,
					person_id: vm.person_id,
					action_id: vm.action_id
				}
				personDataService.resetPassword(data, onResetPasswordSuccess, onResetPasswordError);
			}
		}
	}

	angular.module('todevise')
	.controller('loginCtrl', controller);

}());