(function () {
	"use strict";

	function controller(UtilService, personDataService, settingsEvents, $scope, $rootScope) {
		var vm = this;
		vm.bank_location = [{
			country_name: 'Australia',
			country_code: 'AU'
		},{
			country_name: 'Canada',
			country_code: 'CA'
		}, {
			country_name: 'New Zealand',
			country_code: 'NZ'
		}, {
			country_name: 'United States',
			country_code: 'US'
		}, {
			country_name: 'Rest of the world',
			country_code: 'OTHER'
		}]
		vm.bank_information = {
			location: 'OTHER'
		}
		vm.resetBankInfo = resetBankInfo;
		vm.saveBankInformation = saveBankInformation;

		init();

		function init(){
			getDeviser();
		}

		function getDeviser() {
			function onGetProfileSuccess(data) {
				vm.deviser = angular.copy(data);
				vm.deviser_original = angular.copy(data);
			}

			personDataService.getProfile({
				personId: person.short_id
			}, onGetProfileSuccess, UtilService.onError);
		}

		function resetBankInfo(location){
			if(vm.errors) delete vm.errors;
			vm.bankInformationForm.$setPristine();
			vm.bankInformationForm.$submitted = false;
			vm.bank_information = {
				location: location
			}
		}

		function saveBankInformation(form){
			function onUpdateProfileSuccess(data) {
				vm.deviser = angular.copy(data);
				vm.deviser_original = angular.copy(data);
				$rootScope.$broadcast(settingsEvents.changesSaved);
			}

			function onUpdateProfileError(err) {
				$rootScope.$broadcast(settingsEvents.invalidForm);
				form.$setPristine();
				if(UtilService.isObject(err.data.errors)) {
					vm.errors = angular.copy(err.data.errors);
						for(var key in vm.errors) {
							form[key].$setValidity('valid', false);
						}
				}
			}

			form.$setSubmitted();
			if(form.$valid) {
				if(!UtilService.isObject(vm.deviser.settings)) {
					vm.deviser.settings = {};
				}
				vm.deviser.settings.bank_info = angular.copy(vm.bank_information);
				vm.deviser.deviser_id = vm.deviser.id;

				personDataService.updateProfile(vm.deviser, {
					personId: vm.deviser.deviser_id
				}, onUpdateProfileSuccess, onUpdateProfileError);
			} else {
				$rootScope.$broadcast(settingsEvents.invalidForm);
				//TODO: Show errors
			}
		}

		//events
		$scope.$on(settingsEvents.saveChanges, function(event, args) {
			saveBankInformation(vm.bankInformationForm);
		})

		//watches
		$scope.$watch('billingCtrl.bank_information', function(newValue, oldValue) {
			if(vm.bankInformationForm && vm.bankInformationForm.$invalid) {
				for(var key in vm.errors) {
					vm.bankInformationForm[key].$setValidity('valid', true);
				}
				delete vm.errors;
			}
		}, true);

		$scope.$watch('billingCtrl.deviser', function(newValue, oldValue) {
			if(newValue) {
				if(!angular.equals(newValue, vm.deviser_original)) {
					UtilService.setLeavingModal(true);
				} else {
					UtilService.setLeavingModal(false);
				}
			}
		})

	}

	angular.module('settings')
		.controller('billingCtrl', controller);

}());