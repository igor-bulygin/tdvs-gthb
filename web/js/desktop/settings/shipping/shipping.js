(function () {
	"use strict";

	function controller(personDataService, languageDataService, UtilService) {
		var vm = this;
		vm.person = angular.copy(person);
		vm.toggleStatus = toggleStatus;
		vm.addZone = addZone;
		vm.deleteZone = deleteZone;
		vm.status = [];

		init();
		
		function init() {
			if(!vm.person.shipping_settings || !angular.isObject(vm.person.shipping_settings))
				vm.person.shipping_settings = []
			getLanguages();
		}

		function getLanguages() {
			function onGetLanguagesSuccess(data) {
				vm.languages = angular.copy(data.items);
			}

			languageDataService.getLanguages(onGetLanguagesSuccess, UtilService.onError);
		}

		function toggleStatus(index) {
			vm.status[index] = !vm.status[index];
		}

		function addZone() {
			vm.person.shipping_settings.push({
			})
			vm.status.push(true);
		}

		function deleteZone(index) {
			vm.person.shipping_settings.splice(index, 1);
		}

	}

	angular
		.module('todevise')
		.controller('shippingSettingsCtrl', controller);
}());