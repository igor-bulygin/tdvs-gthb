(function () {
	"use strict";

	function controller(UtilService, personDataService, invitationDataService, toastr, $location, $window) {
		var vm = this;
		vm.submitForm = submitForm;
		vm.has_error = UtilService.has_error;

		function init() {
			vm.deviser = Object.assign({}, invitation);
		}

		init();

		function submitForm(form) {
			function onCreateDeviser(data) {
				$window.location.href = '/deviser/' + data.slug + '/' + data.id + '/about/edit';
			}
			if (form.password_confirm.$error.same)
				form.$setValidity('password_confirm', false);
			else {
				form.$setValidity('password_confirm', true);
			}
			if (form.$valid) {

				form.$setSubmitted();
				personDataService.createDeviser(vm.deviser, null, onCreateDeviser, UtilService.onError);
			}
		}

	}

	angular
		.module('todevise', ['api', 'util', 'header', 'toastr', 'ui.bootstrap'])
		.controller('createDeviserCtrl', controller);

}());