(function () {
	"use strict";

	function controller(UtilService) {
		var vm = this;
		vm.has_error = UtilService.has_error;
	}

	var component ={
		templateUrl: currentHost() + '/js/desktop/settings/billing/australia/australia.html',
		controller: controller,
		controllerAs: 'australiaBankInformationCtrl',
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
		.component('australiaBankInformation', component);

}());