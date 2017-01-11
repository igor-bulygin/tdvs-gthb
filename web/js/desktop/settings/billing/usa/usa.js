(function () {
	"use strict";

	function controller() {
		var vm = this;
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
			bankInformation: '<'
		}
	}

	angular
		.module('todevise')
		.component('usaBankInformation', component);

}());