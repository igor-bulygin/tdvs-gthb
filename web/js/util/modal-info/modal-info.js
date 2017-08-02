(function () {
	"use strict";

	function controller() {
		var vm = this;
		vm.ok = ok;
		vm.dismiss = dismiss;

		function init() {
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
		templateUrl: currentHost() + '/js/util/modal-info/modal-info.html',
		controller: controller,
		controllerAs: 'modalInfoCtrl',
		bindings: {
			resolve: '<',
			close: '&',
			dismiss: '&'
		}
	}

	angular
		.module('util')
		.component('modalInfo', component);

}());