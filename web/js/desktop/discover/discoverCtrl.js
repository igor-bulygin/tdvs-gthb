(function () {
	"use strict";

	function controller(personDataService, UtilService) {
		var vm = this;
		vm.search = search;

		init();

		function init() {

		}

		function search(form) {
			function onGetPeopleSuccess(data) {
				vm.results = angular.copy(data);
			}

			vm.key_search = angular.copy(vm.key)

			personDataService.getPeople({
				q: vm.key_search,
				type: type
			}, onGetPeopleSuccess, UtilService.onError);
		}
	}

	angular.module('todevise')
		.controller('discoverCtrl', controller);

}());