(function () {
	"use strict";

	function controller(UtilService) {
		var vm = this;
		vm.has_error = UtilService.has_error;
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/settings/billing/newZealand/newZealand.html',
		controller: controller,
		controllerAs: 'newZealandBankInformationCtrl',
		require: {
			form: '^form'
		},
		bindings: {
			bankInformation: '<',
			errors: '<'
		}
	}

	angular
		.module('settings')
		.component('newZealandBankInformation', component);
}());