(function () {
	"use strict";

	function controller(UtilService) {
		var vm = this;
		vm.has_error = UtilService.has_error;
		vm.account_types = [{
			name: 'Checking',
			value: 'checking'
		},
		{
			name: 'Savings',
			value: 'savings'
		}]
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/settings/billing/usa/usa.html',
		controller: controller,
		controllerAs: 'usaBankInformationCtrl',
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
		.component('usaBankInformation', component);

}());