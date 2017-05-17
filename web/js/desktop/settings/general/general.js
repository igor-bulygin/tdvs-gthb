(function () {
	"use strict";

	function controller(personDataService,UtilService) {
		var vm = this;
		vm.person = {id:person.id, personal_info:angular.copy(person.personal_info), settings:angular.copy(person.settings)};
		init();
		vm.update=update;
		vm.saving=false;
		vm.saved=false;
		
		function init() {
			getCurrencies();
		}

		function getCurrencies() {
			function onGetCurrenciesSuccess(data) {
				vm.currencies = data;
			}
			personDataService.getCurrencies(onGetCurrenciesSuccess, UtilService.onError);
		}

		function update() {
			vm.saving=true;
			function onUpdateGeneralSettingsSuccess(data) {
				vm.saving=false;
				vm.saved=true;
				vm.dataForm.$dirty=false;
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