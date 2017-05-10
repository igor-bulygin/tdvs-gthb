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
		vm.person = angular.copy(person);

		init();

		function init() {
			
		}
	}

	angular	
		.module('todevise')
		.controller('generalSettingsCtrl', controller);

}());