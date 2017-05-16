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

		vm.person = {id:person.id, personal_info:angular.copy(person.personal_info)};
		vm.update=update;
		init();

		function init() {
		}

		function update() {
			vm.saving=true;
			function onUpdateGeneralSettingsSuccess(data) {
				vm.saving=false;
				//$window.location.href = currentHost() + data.view_link;
			}

			function onUpdateGeneralSettingsError(data) {
				vm.saving=false;
			 }

			personDataService.updateProfile(vm.person,{personId: vm.person.id}, onUpdateGeneralSettingsSuccess, onUpdateGeneralSettingsError);
		}
	}

	angular	
		.module('todevise')
		.controller('generalSettingsCtrl', controller);

}());