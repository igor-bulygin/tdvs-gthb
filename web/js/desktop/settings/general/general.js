(function () {
	"use strict";

	function controller(personDataService, UtilService) {
		var vm = this;
		//TODO: Get currencies via API
		vm.currencies = [{
			"symbol": "€",
			"text": "EURO",
			"value": "EUR" 
		}]

		init();

		function init() {
			getPerson();
		}

		function getPerson() {
			function onGetPersonSuccess(data) {
				vm.person = angular.copy(data);
			}

			personDataService.getProfile({
				personId: person.short_id
			}, onGetPersonSuccess, UtilService.onError);
		}
	}

	angular	
		.module('todevise')
		.controller('generalSettingsCtrl', controller);

}());