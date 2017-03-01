(function () {
	"use strict";

	function controller(personDataService, $window, UtilService) {
		var vm = this;
		vm.logout = logout;

		function logout(){
			function onLogoutSuccess(data) {
				UtilService.removeLocalStorage('access_token');
				UtilService.removeLocalStorage('cart_id');
				$window.location.href = currentHost();
			}

			personDataService.logout(vm.session_logout, null, onLogoutSuccess, UtilService.onError);
		}
	}

	angular.module('header', ['api', 'util', 'box'])
		.controller('publicHeaderCtrl', controller);
}());