(function () {
	"use strict";

	function controller(UtilService) {
		var vm = this;
		vm.has_error = UtilService.has_error;
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/settings/billing/other/otherBankInformation.html',
		controller: controller,
		controllerAs: 'otherBankInformationCtrl',
		require: {
			form: '^form'
		},
		bindings: {
			bankInformation: '<',
			errors: '<'
		}
	}

	angular
		.module('todevise')
		.component('otherBankInformation', component);

}());