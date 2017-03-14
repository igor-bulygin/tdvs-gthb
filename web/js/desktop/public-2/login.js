(function () {
	"use strict";

	function controller(personDataService, $window, UtilService,localStorageUtilService) {
		var vm = this;
		vm.login = login;

		function login() {
			function onLoginSuccess(data) {
				localStorageUtilService.setLocalStorage('access_token', data.access_token);
				$window.location.href = currentHost() + data.return_url;
			}

			function onLoginError(err) {
				UtilService.onError(err);
				vm.errors = true;
			}

			personDataService.login(vm.user, null, onLoginSuccess, onLoginError);
		}
	}

angular.module('todevise', ['api', 'util', 'header'])
	.controller('loginCtrl', controller);

}());