(function () {
	"use strict";

	function controller(deviserDataService, $window, UtilService) {
		var vm = this;
		vm.login = login;

		init();

		function init() {
			vm.user = new deviserDataService.Login;
		}

		function login() {
			vm.user.$save().then(function (loginData) {
				UtilService.setLocalStorage('access_token', loginData.access_token);
				$window.location.href = currentHost();
			}, function (err) {
				vm.errors = true;
			});
		}
	}

angular.module('todevise', ['api', 'util'])
	.controller('loginCtrl', controller);

}());