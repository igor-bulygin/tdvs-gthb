(function () {
	"use strict";

	function controller(UtilService, deviserDataService, settingsEvents, $scope, $rootScope) {
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
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function(dataDeviser) {
					vm.deviser = angular.copy(dataDeviser);
				}, function(err){
					//err
				});
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
			form.$setSubmitted();
			if(form.$valid) {
				if(!UtilService.isObject(vm.deviser.settings)) {
					vm.deviser.settings = {};
				}
				vm.deviser.settings.bank_info = angular.copy(vm.bank_information);
				vm.deviser.deviser_id = vm.deviser.id;
				vm.deviser.$update().then(function (dataDeviser) {
					vm.deviser = angular.copy(dataDeviser);
					$rootScope.$broadcast(settingsEvents.changesSaved);
				}, function (err) {
					$rootScope.$broadcast(settingsEvents.invalidForm);
					form.$setPristine();
					if(UtilService.isObject(err.data.errors)) {
						vm.errors = angular.copy(err.data.errors);
						for(var key in vm.errors) {
							form[key].$setValidity('valid', false);
						}
					}
				})
			} else {
				$rootScope.$broadcast(settingsEvents.invalidForm);
				//TODO: Show errors
			}
		}

		$scope.$on(settingsEvents.saveChanges, function(event, args) {
			saveBankInformation(vm.bankInformationForm);
		})

		$scope.$watch('billingCtrl.bank_information', function(newValue, oldValue) {
			if(vm.bankInformationForm.$invalid) {
				for(var key in vm.errors) {
					vm.bankInformationForm[key].$setValidity('valid', true);
				}
				delete vm.errors;
			}
		}, true);
	}

	angular.module('todevise')
		.controller('billingCtrl', controller);

}());