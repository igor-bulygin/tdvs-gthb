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
		vm.city = vm.person.personal_info.city + ', ' + vm.person.personal_info.country;
		init();
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
		
		function init() {
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

		function update() {
			vm.saved=false;
			if (angular.isUndefined(vm.person.settings.weight_measure) || vm.person.settings.weight_measure === null ) {
				vm.notWeightMeasureSelected=true;
			}
			if (vm.dataForm.$valid) {
				if (vm.person.personal_info.phone_number_prefix.indexOf('+') == -1) {
					vm.person.personal_info.phone_number_prefix='+' + vm.person.personal_info.phone_number_prefix;
				}
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

		function existRequiredError(fieldName, form) {
			var exists=false;
			if (angular.isUndefined(form)) {
				return exists;
			}			
			angular.forEach(form.$error.required, function(value){
				if (!angular.isUndefined(value.$name) && value.$name==fieldName) {
					exists=true;
				}
			});
			return exists;
		}
	}

	angular	
	.module('todevise')
	.controller('generalSettingsCtrl', controller);

}());