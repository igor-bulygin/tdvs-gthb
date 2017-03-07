(function () {
	"use strict";

	function controller(UtilService, personDataService, $location, $window) {
		var vm = this;
		vm.submitForm = submitForm;
		vm.has_error = UtilService.has_error;

		function init() {

		}

		init();

		function submitForm(form) {
			var url = "";
			function onLoginSuccess(data) {
				UtilService.setLocalStorage('access_token', data.access_token);
				$window.location.href = url;
			}

			function onCreateClientSuccess(data) {
				url = data.about_link;
				personDataService.login(vm.user, null, onLoginSuccess, UtilService.onError);
			}

			if (form.password_confirm.$error.same)
				form.$setValidity('password_confirm', false);
			else {
				form.$setValidity('password_confirm', true);
			}
			if (form.$valid) {
				form.$setSubmitted();
				personDataService.createClient(vm.user, null, onCreateClientSuccess, UtilService.onError);
			}
		}

	}

	angular
		.module('todevise', ['api', 'util', 'header', 'ui.bootstrap'])
		.controller('createClientCtrl', controller);

}());