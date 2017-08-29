(function () {
	"use strict";

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

	var headerModule=angular.module('header', ['api', 'util', 'box','pascalprecht.translate'])
	.controller('publicHeaderCtrl', controller);
	headerModule.config(['$translatePartialLoaderProvider', function ($translatePartialLoaderProvider) {
		$translatePartialLoaderProvider.addPart('header');
	}]);
}());