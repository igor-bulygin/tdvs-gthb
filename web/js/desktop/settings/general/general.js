(function () {
	"use strict";

	function controller(personDataService,UtilService,locationDataService,$uibModal, metricDataService,$scope) {
		var vm = this;
		vm.person = {id:person.id, personal_info:angular.copy(person.personal_info), settings:angular.copy(person.settings)};
		vm.isDeviser=false;
		if (person.type[0]==2) {
			vm.isDeviser=true;
		}
		vm.notWeightMeasureSelected=false;
		vm.city ="";
		if (!angular.isUndefined(vm.person.personal_info.city) && vm.person.personal_info.city.length>0) {
			vm.city=vm.person.personal_info.city;
			if (!angular.isUndefined(vm.person.personal_info.country) && vm.person.personal_info.country.length>0) {
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
		vm.setPrefix=setPrefix;
		vm.invalidPrefix=false;

		init();
		
		function init() {
			vm.setPrefix();
			if (vm.person.personal_info.phone_number_prefix.length<2) {
				vm.invalidPrefix=true;
			}
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
			if (angular.isUndefined(vm.person.personal_info.phone_number_prefix)) {
				vm.person.personal_info.phone_number_prefix="+";
			}
			if (vm.person.personal_info.phone_number_prefix.indexOf('+') == -1) {
				vm.person.personal_info.phone_number_prefix='+' + vm.person.personal_info.phone_number_prefix;
			}
		}

		function update() {
			vm.saved=false;
			if (angular.isUndefined(vm.person.settings.weight_measure) || vm.person.settings.weight_measure === null || vm.person.settings.weight_measure.length<1 ) {
				vm.notWeightMeasureSelected=true;
			}
			if (isValidForm() && !vm.invalidPrefix) {
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
			return (((vm.isDeviser && vm.dataForm.brand_name.length>0) || !vm.isDeviser) 
				&& vm.dataForm.city.length>0 && vm.dataForm.street.length>0 && vm.dataForm.street.length>0 && !vm.invalidPrefix  && vm.dataForm.street.length>0 
				&& vm.dataForm.phone.length>0  && vm.dataForm.number.length>0  && vm.dataForm.zip.length>0 && !vm.notWeightMeasureSelected)
		}

		function openModal() {
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
			return (value.length<1 && vm.showInvalid);
		}

		function existPaswordRequiredError(value) {
			if (angular.isUndefined(value)) {
				return vm.showInvalid;
			}
			return (value.length<1 && vm.showPasswordErrors);
		}
	}

	angular	
	.module('todevise')
	.controller('generalSettingsCtrl', controller);

}());