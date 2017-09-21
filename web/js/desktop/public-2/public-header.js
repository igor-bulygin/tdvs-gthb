(function () {
	"use strict";

	function config($translatePartialLoaderProvider) {
		$translatePartialLoaderProvider.addPart('header');
	}

	function controller(personDataService, $window, UtilService, localStorageUtilService) {
		var vm = this;
		vm.logout = logout;

		function logout(){
			function onLogoutSuccess(data) {
				localStorageUtilService.removeLocalStorage('access_token');
				localStorageUtilService.removeLocalStorage('cart_id');
				$window.location.href = currentHost();
			}

			personDataService.logout(vm.session_logout, null, onLogoutSuccess, UtilService.onError);
		}
	}

	angular.module('header', ['api', 'util', 'box', 'pascalprecht.translate'])
		.config(config)
		.controller('publicHeaderCtrl', controller);
}());