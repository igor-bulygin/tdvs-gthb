(function () {
	"use strict";

	function controller(UtilService, personDataService, $window, localStorageUtilService, $cookieStore, $location, $anchorScroll) {
		var vm = this;
		vm.has_error = UtilService.has_error;
		vm.login = login;
		vm.signUp = signUp;
		vm.goToLogin = goToLogin;

		function onLoginSuccess(data) {
			if(data.access_token) {
				localStorageUtilService.setLocalStorage('access_token', data.access_token);
				$cookieStore.put('sesion_id', data.short_id);
			}
			if(data.return_url)
				$window.location.href = data.return_url;
		}

		function login(form) {
			function onLoginError(err) {
				vm.loading=false;
				console.log(err);
			}

			vm.loading = true;

			form.$setSubmitted();
			if(form.$valid) {
				personDataService.login(vm.login_user, null, onLoginSuccess, onLoginError);
			}
		}

		function signUp(form) {
			vm.loading = true;
			function onCreatePersonSuccess(data) {
				personDataService.login(vm.user, null, onLoginSuccess, UtilService.onError);
			}

			function onCreatePersonError(err) {
				vm.loading = false;
				console.log(err);
				if(err.status === 409)
					vm.error_message = "This account already exists.";
			}

			if(form.password_confirm.$error.same) {
				vm.loading = false;
				form.$setValidity('password_confirm', false);
			}
			else {
				form.$setValidity('password_confirm', true);
			}
			form.$setSubmitted();
			if(form.$valid) {
				personDataService.createClient(vm.user, null, onCreatePersonSuccess, onCreatePersonError);
			}
		}

		function goToLogin() {
			$location.hash('already-user-login');
            $anchorScroll();
		}
	}

	angular
		.module('todevise')
		.controller('authenticationRequiredCtrl', controller);

}());
