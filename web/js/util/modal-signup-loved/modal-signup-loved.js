(function () {
	"use strict";

	function controller(){
		var vm = this;
		vm.login = currentHost() + '/login';
		vm.signup = currentHost() + '/signup';
	}

	var component = {
		templateUrl: currentHost() + '/js/util/modal-signup-loved/modal-signup-loved.html',
		controller: controller,
		controllerAs: 'modalSignUpLovedCtrl',
		bindings: {
			dismiss: '&',
			close: '&',
			resolve: '<'
		}
	}

	angular
		.module('util')
		.component('modalSignUpLoved', component);

}());