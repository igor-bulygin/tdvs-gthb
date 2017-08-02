(function () {
	"use strict";

	function controller() {
		var vm = this;
		vm.ok = ok;
		vm.dismiss = dismiss;

		function init() {
		}

		init();

		function closeMe(success) {
			vm.close({
				$value: success
			})
		}

		function ok() {
			closeMe(true);
		}

		function dismiss(){
			closeMe(false);
		}
	}

	var component = {
		templateUrl: currentHost() + '/js/util/modal-acept-reject/modal-acept-reject.html',
		controller: controller,
		controllerAs: 'modalAceptRejectCtrl',
		bindings: {
			resolve: '<',
			close: '&',
			dismiss: '&'
		}
	}

	angular
		.module('util')
		.component('modalAceptReject', component);

}());