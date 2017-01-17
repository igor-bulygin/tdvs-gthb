(function () {
	"use strict";

	function controller(settingsEvents, $rootScope) {
		var vm = this;
		vm.saveChanges = saveChanges;

		function saveChanges() {
			$rootScope.$broadcast(settingsEvents.saveChanges);
		}
	}


	angular
		.module('todevise')
		.controller('settingsHeaderCtrl', controller);
}());