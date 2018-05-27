(function () {
	"use strict";

	function controller() {
		var vm = this;
		vm.ok = ok;
		vm.dismiss = dismiss;

		init();

		function init() {
		}

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
		templateUrl: currentHost() + '/js/desktop/person/social-feed/modal-post-details/modal-post-details.html',
		controller: controller,
		controllerAs: 'modalPostDetailsCtrl',
		bindings: {
			resolve: '<',
			close: '&',
			dismiss: '&'
		}
	}

	angular
		.module('person')
		.component('modalPostDetails', component);

}());