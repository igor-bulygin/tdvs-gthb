(function () {
	"use strict";

	function controller(UtilService, personDataService, $window, localStorageUtilService) {
		var vm = this;
		vm.submitForm = submitForm;
		vm.has_error = UtilService.has_error;

		init();

		function init() {
			if(typeof invitation != 'undefined' && typeof invitation == 'object')
				vm.person = Object.assign({}, invitation);
			if(typeof type != 'undefined' && typeof type == 'number')
				vm.type = type;
			else {
				vm.type = 1;
			}
		}

		function submitForm(form) {
			var url = "";

			function onLoginSuccess(data) {
				if(data.access_token) {
					localStorageUtilService.setLocalStorage('access_token', data.access_token);
					$window.location.href = url;
				}
			}

			function onCreatePersonSuccess(data) {
				url = data.main_link;
				personDataService.login(vm.person, null, onLoginSuccess, UtilService.onError);
			}

			function onCreatePersonError(err) {
				console.log(err);
				if(err.status === 409)
					vm.error_message = "This account already exists.";
			}

			if(form.password_confirm.$error.same)
				form.$setValidity('password_confirm', false);
			else {
				form.$setValidity('password_confirm', true);
			}
			form.$setSubmitted();
			if(form.$valid) {
				switch(vm.type) {
					case 2:
						personDataService.createInfluencer(vm.person, null, onCreatePersonSuccess, onCreatePersonError);
						break;
					case 3:
						personDataService.createDeviser(vm.person, null, onCreatePersonSuccess, onCreatePersonError);
						break;
					default: 
						personDataService.createClient(vm.person, null, onCreatePersonSuccess, onCreatePersonError);
						break;
				}
			}
		}

	}

	angular
		.module('todevise')
		.controller('createAccountCtrl', controller);
}());