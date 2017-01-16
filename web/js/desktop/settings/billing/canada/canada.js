(function () {
	"use strict";

	function controller(UtilService) {
		var vm = this;
		vm.has_error = UtilService.has_error;
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