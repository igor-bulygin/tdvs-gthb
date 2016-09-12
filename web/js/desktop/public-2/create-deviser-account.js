(function () {
	"use strict";

	function controller(UtilService) {
		var vm = this;
		vm.submit = submit;
		vm.has_error = UtilService.has_error;

		function init() {

		}

		init();

		function submit(form) {
			form.$setSubmitted();
			if (form.$valid) {

			} else {
				toastr.error("Invalid form!")
			}
		}

	}


	angular
		.module('todevise', ['api', 'util'])
		.controller('createDeviserCtrl', controller);

}());