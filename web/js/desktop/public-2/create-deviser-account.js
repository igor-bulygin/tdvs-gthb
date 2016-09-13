(function () {
	"use strict";

	function controller(UtilService, deviserDataService, toastr) {
		var vm = this;
		vm.submitForm = submitForm;
		vm.has_error = UtilService.has_error;

		function init() {
			vm.deviser = new deviserDataService.Devisers;
		}

		init();

		function submitForm(form) {
			if (form.password_confirm.$error.same)
				form.$setValidity('password_confirm', false);
			if (form.$valid) {
				form.$setSubmitted();
				vm.deviser.$save().then(function (dataSaved) {
					//ok
				}, function (err) {
					toastr.error("Error saving form!");
				})
			} else {
				toastr.error("Invalid form!")
			}
		}

	}


	angular
		.module('todevise', ['api', 'util', 'toastr'])
		.controller('createDeviserCtrl', controller);

}());