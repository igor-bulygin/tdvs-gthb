(function () {
	"use strict";

	function controller() {
		var vm = this;
		vm.ok = ok;
		vm.dismiss = dismiss;

		function init() {
			console.log("init modal");
		}

		init();

		function ok() {
			vm.close({
				$value: vm.resolve.link
			})
		}

		function dismiss(){
			vm.close();
		}
	}

	var component = {
		templateUrl: currentHost() + '/js/util/modal-confirm-leave/modal-confirm-leave.html',
		controller: controller,
		controllerAs: 'confirmLeaveCtrl',
		bindings: {
			resolve: '<',
			close: '&',
			dismiss: '&'
		}
	}

	angular
		.module('util')
		.component('modalConfirmLeave', component);

}());