(function () {
	"use strict";

	function controller() {
		var vm = this;
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/settings/billing/newZealand/newZealand.html',
		controller: controller,
		controllerAs: 'newZealandBankInformationCtrl',
		require: {
			form: '^form'
		},
		bindings: {
			bankInformation: '<'
		}
	}

	angular
		.module('todevise')
		.component('newZealandBankInformation', component);
}());