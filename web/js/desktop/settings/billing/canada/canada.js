(function () {
	"use strict";

	function controller() {
		var vm = this;
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/settings/billing/canada/canada.html',
		controller: controller,
		controllerAs: 'canadaBankInformationCtrl',
		require: {
			form: '^form'
		},
		bindings: {
			bankInformation: '<'
		}
	}

	angular.module('todevise')
		.component('canadaBankInformation', component);
}());