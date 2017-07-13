(function () {
	"use strict";

	function controller(UtilService) {
		var vm = this;
		vm.has_error = UtilService.has_error;
		vm.login = login;
		vm.signUp = signUp;

		function login(form) {
			
		}

		function signUp(form) {

		}


	}

	angular
		.module('todevise')
		.controller('authenticationRequiredCtrl', controller);

}());