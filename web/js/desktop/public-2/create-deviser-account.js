(function () {
	"use strict";

	function controller(UtilService, personDataService, invitationDataService, $location, $window, localStorageUtilService) {
		var vm = this;
		vm.submitForm = submitForm;
		vm.has_error = UtilService.has_error;

		function init() {
			vm.deviser = Object.assign({}, invitation);
		}

		init();

		function submitForm(form) {
			var url = "";

			function onLoginSuccess(data){
				localStorageUtilService.setLocalStorage('access_token', data.access_token);
				$window.location.href = url;
			}

			function onCreateDeviserSuccess(data) {
				url = data.main_link;
				personDataService.login(vm.deviser, null, onLoginSuccess, UtilService.onError);
			}
			if (form.password_confirm.$error.same)
				form.$setValidity('password_confirm', false);
			else {
				form.$setValidity('password_confirm', true);
			}
			if (form.$valid) {
				form.$setSubmitted();
				personDataService.createDeviser(vm.deviser, null, onCreateDeviserSuccess, UtilService.onError);
			}
		}

	}

	angular
		.module('todevise', ['api', 'util', 'header', 'ui.bootstrap'])
		.controller('createDeviserCtrl', controller);

}());