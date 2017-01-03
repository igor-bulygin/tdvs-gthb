(function () {
	"use strict";

	function controller(deviserDataService, $window, UtilService) {
		var vm = this;
		vm.logout = logout;

		init();

		function init(){
			vm.session_logout = new deviserDataService.Logout;
		}

		function logout(){
			vm.session_logout.$save().then(function (logoutData) {
				UtilService.removeLocalStorage('access_token');
				$window.location.href = currentHost();
			}, function(err) {
				console.log(err);
			});
		}
	}

	angular.module('header', ['api', 'util'])
		.controller('publicHeaderCtrl', controller);
}());