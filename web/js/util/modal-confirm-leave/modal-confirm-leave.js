(function () {
	"use strict";

	function controller() {
		var vm = this;
	}

	var component = {
		templateUrl: currentHost() + '/js/util/modal-confirm-leave/modal-confirm-leave.html',
		controller: controller,
		controllerAs: 'confirmLeaveCtrl',
		bindings: {
		}
	}

	angular
		.module('util')
		.component('modalConfirmLeave', component);

}());