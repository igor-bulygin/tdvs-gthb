(function () {
	"use strict";

	function controller(personDataService) {
		var vm = this;
		//TODO: Get currencies via API
		vm.currencies = [{
			"symbol": "€",
			"text": "EURO",
			"value": "EUR" 
		}];
		vm.person=angular.copy(person);
		vm.counter=0;
		init();

		function init() {
		}

		function count() {
			vm.counter=vm.counter+1;
		}
	}

	angular	
		.module('todevise')
		.controller('generalSettingsCtrl', controller);

}());