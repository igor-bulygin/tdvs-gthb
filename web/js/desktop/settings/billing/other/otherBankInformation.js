(function () {
	"use strict";

	function controller() {
		var vm = this;
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/settings/billing/other/otherBankInformation.html',
		controller: controller,
		controllerAs: 'otherBankInformationCtrl',
		require: {
			form: '^form'
		},
		bindings: {
			bankInformation: '<'
		}
	}

	angular
		.module('todevise')
		.component('otherBankInformation', component);

}());