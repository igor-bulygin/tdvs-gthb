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
			function onCreateClient(data) {
				$window.location.href = data.about_edit_link;
			}
			if (form.password_confirm.$error.same)
				form.$setValidity('password_confirm', false);
			else {
				form.$setValidity('password_confirm', true);
			}
			if (form.$valid) {
				form.$setSubmitted();
				personDataService.createClient(vm.user, null, onCreateClient, UtilService.onError);
			}
		}

	}

	angular
		.module('todevise', ['api', 'util', 'header', 'ui.bootstrap'])
		.controller('createClientCtrl', controller);

}());