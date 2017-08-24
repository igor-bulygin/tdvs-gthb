(function () {
	"use strict";

	function controller() {
		var vm = this;
		vm.ok = ok;
		vm.dismiss = dismiss;

		function init() {
			if (angular.isUndefined(vm.resolve.text)) {
				vm.resolve.text="";
			}
			if (angular.isUndefined(vm.resolve.showButton)) {
				vm.resolve.showButton=true;
			}
			if (angular.isUndefined(vm.resolve.title)) {
				vm.resolve.title="";
			}
			if (angular.isUndefined(vm.resolve.translationData)) {
				vm.resolve.translationData="";
			}
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