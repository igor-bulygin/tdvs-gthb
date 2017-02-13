(function () {
	"use strict";

	function controller(){
		var vm = this;
		vm.login = currentHost() + '/login';
		vm.signup = currentHost(); //add signup
	}

	var component = {
		templateUrl: currentHost() + '/js/util/modal-signup-loved/modal-signup-loved.html',
		controller: controller,
		controllerAs: 'modalSignUpLovedCtrl',
		bindings: {
			dismiss: '&',
			close: '&'
		}
	}

	angular
		.module('util')
		.component('modalSignUpLoved', component);

}());