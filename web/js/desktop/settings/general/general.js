(function () {
	"use strict";

	function controller(personDataService, UtilService, locationDataService, $uibModal, metricDataService, $scope) {
		var vm = this;
		vm.person = {id:person.id, personal_info:angular.copy(person.personal_info), settings:angular.copy(person.settings)};
		
		vm.isDeviser=false;
		if (person.type[0]==2) {
			vm.isDeviser=true;
		}
		vm.notWeightMeasureSelected=false;
		vm.city ="";
		if (!angular.isUndefined(vm.person.personal_info.city) && angular.isString(vm.person.personal_info.city)) {
			vm.city=vm.person.personal_info.city;
			if (!angular.isUndefined(vm.person.personal_info.country) && angular.isString(vm.person.personal_info.country)) {
				vm.city =vm.city + ', ' + vm.person.personal_info.country;
			}
		}		
		vm.update=update;
		vm.saving=false;
		vm.saved=false;
		vm.existRequiredError=existRequiredError;
		vm.searchPlace=searchPlace;
		vm.selectCity=selectCity;
		vm.showCities = false;
		vm.updatePassword=updatePassword;
		vm.distinctPasswords=false;
		vm.showPasswordErrors=false;
		vm.openModal=openModal;
		vm.passwordModal=null;
		vm.weightCharged=false;
		vm.dismiss=dismiss;
		vm.showInvalid=false;		
		vm.invalidPrefix=false;

		init();
		
		function init() {	
			setPrefix();			
			vm.person_original = angular.copy(vm.person);
			getWeightUnits();
		}

		function getWeightUnits() {
			function onGetMetricSuccess(data) {
				vm.weightMeasures=[];
				if (!angular.isUndefined(data) && !angular.isUndefined(data.weight)) {
					vm.weightMeasures = data.weight;
				}
				vm.weightCharged=true;
			}
			vm.weightCharged=false;
			metricDataService.getMetric({},onGetMetricSuccess, UtilService.onError);
		}

		function searchPlace(place) {
			if (place.length>2) {
				function onGetLocationSuccess(data) {
					if(data.items.length === 0) {
						vm.showCities = false;
					}
					if(data.items.length > 0) {
						vm.showCities = true;
						vm.cities = angular.copy(data.items);
					}
				}
				if(place)
					locationDataService.getLocation({q: place}, onGetLocationSuccess, UtilService.onError);
				else {
					selectCity({
						city: null,
						country_code: null
					})
				}
			}
		}

		function selectCity(city) {
			vm.person.personal_info.city = city.city;
			vm.person.personal_info.country = city.country_code;
			if(city.city && city.country_code)
				vm.city = vm.person.personal_info.city + ', ' + vm.person.personal_info.country;
			vm.showCities = false;
		}

		function setPrefix() {
			if (vm.person.personal_info.phone_number_prefix==null || angular.isUndefined(vm.person.personal_info.phone_number_prefix) || vm.person.personal_info.phone_number_prefix.length<1) {
				vm.person.personal_info.phone_number_prefix="+";
			}
			if (vm.person.personal_info.phone_number_prefix.indexOf('+') == -1) {
				vm.person.personal_info.phone_number_prefix='+' + vm.person.personal_info.phone_number_prefix;
			}
		}

		function update() {
			vm.saved=false;
			vm.invalidPrefix=false;
			setPrefix();
			if (angular.isUndefined(vm.person.settings) || vm.person.settings===null) {
				vm.person.settings={};
			}
			if (angular.isUndefined(vm.person.settings.weight_measure) || vm.person.settings.weight_measure === null || vm.person.settings.weight_measure.length<1 ) {
				vm.notWeightMeasureSelected=true;
			}
			if (vm.person.personal_info.phone_number_prefix.length<2) {
				vm.invalidPrefix=true;
			}
			if (isValidForm()) {
				vm.saving=true;
				function onUpdateGeneralSettingsSuccess(data) {
					vm.saving=false;
					vm.showInvalid=false;
					vm.saved=true;
					vm.dataForm.$dirty=false;
				}
				function onUpdateGeneralSettingsError(data) {
					vm.saving=false;
				}
				personDataService.updateProfile(vm.person,{personId: vm.person.id}, onUpdateGeneralSettingsSuccess, onUpdateGeneralSettingsError);
			}
			else {
				vm.showInvalid=true;
			}
		}
		function isValidForm() {
			return (((vm.isDeviser && !angular.isUndefined(vm.dataForm.brand_name.$viewValue) && vm.dataForm.brand_name.$viewValue.length>0) || !vm.isDeviser) 
				&& !angular.isUndefined(vm.dataForm.city.$viewValue) && vm.dataForm.city.$viewValue.length>0 && !angular.isUndefined(vm.dataForm.street.$viewValue)  && vm.dataForm.street.$viewValue.length>0 
				 && !vm.invalidPrefix && !angular.isUndefined(vm.dataForm.phone.$viewValue) && vm.dataForm.phone.$viewValue.length>0  
				&& !angular.isUndefined(vm.dataForm.number.$viewValue) && vm.dataForm.number.$viewValue.length>0 
				&& !angular.isUndefined(vm.dataForm.zip.$viewValue) && vm.dataForm.zip.$viewValue.length>0 && !vm.notWeightMeasureSelected)
		}

		function openModal() {
			vm.currentPassword="";
			vm.newPassword="";
			vm.newPasswordBis="";
			vm.passwordModal=$uibModal.open({
				templateUrl: 'passwordModal',
				scope: $scope,
				size: 'sm'
			});
		}

		function dismiss() {
			vm.passwordModal.close();
		}

		function updatePassword() {
			vm.errorMsg='';
			vm.distinctPasswords=false;
			vm.showPasswordErrors=false;
			if (!vm.passwordForm.$valid) {
				vm.showPasswordErrors=true;
				return;
			}
			if (vm.newPassword != vm.newPasswordBis) {
				vm.distinctPasswords=true;
				return;
			}
			vm.savingPassword=true;
			function onUpdatePasswordSuccess(data) {
				vm.savingPassword=false;
				vm.saved=true;
				vm.dataForm.$dirty=false;
				vm.passwordModal.close();
			}
			function onUpdatePasswordError(err) {
				vm.savingPassword=false;
				vm.errorMsg=err.data.message;
			}
			personDataService.updatePassword({oldpassword:vm.currentPassword, newpassword:vm.newPassword},{personId: vm.person.id}, onUpdatePasswordSuccess, onUpdatePasswordError);
		}

		function existRequiredError(value) {
			if (angular.isUndefined(value)) {
				return vm.showInvalid;
			}
			return (value && value.length<1 && vm.showInvalid);
		}

		function existPaswordRequiredError(value) {
			if (angular.isUndefined(value)) {
				return vm.showInvalid;
			}
			return (value.length<1 && vm.showPasswordErrors);
		}

		//watches
		$scope.$watch('generalSettingsCtrl.person', function (newValue, oldValue) {
			if(newValue) {
				if(!angular.equals(newValue, vm.person_original)) {
					UtilService.setLeavingModal(true);
				} else {
					UtilService.setLeavingModal(false);
				}
			}
		}, true);
	}

	angular	
	.module('settings')
	.controller('generalSettingsCtrl', controller);

}());