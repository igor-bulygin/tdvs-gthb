(function () {
	"use strict";

	function controller(personDataService, $window, UtilService,localStorageUtilService) {
		var vm = this;
		vm.login = login;
		vm.loading=false;

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
	}

angular.module('todevise')
	.controller('loginCtrl', controller);

}());