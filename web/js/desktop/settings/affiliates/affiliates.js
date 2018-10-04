(function () {
	"use strict";

	function controller(personDataService, UtilService, locationDataService, $uibModal, metricDataService, $scope) {
		var vm = this;
		vm.person = {id:person.id, personal_info:angular.copy(person.personal_info), settings:angular.copy(person.settings)};

		vm.isDeviser=false;
		if (person.type[0]==2) {
			vm.isDeviser=true;
		}

    vm.isDeviserOrInfluencer=false;
    if (person.type[0]==2 || person.type[0]==3) {
			vm.isDeviserOrInfluencer=true;
		}

    // Split IBAN number
    if(vm.person.personal_info.iban !== null) {
      vm.person.personal_info.iban0 = (vm.person.personal_info.iban !== undefined) ? vm.person.personal_info.iban.substring(0,4) : '';
      vm.person.personal_info.iban1 = (vm.person.personal_info.iban !== undefined) ? vm.person.personal_info.iban.substring(4,8) : '';
      vm.person.personal_info.iban2 = (vm.person.personal_info.iban !== undefined) ? vm.person.personal_info.iban.substring(8,12) : '';
      vm.person.personal_info.iban3 = (vm.person.personal_info.iban !== undefined) ? vm.person.personal_info.iban.substring(12,16) : '';
      vm.person.personal_info.iban4 = (vm.person.personal_info.iban !== undefined) ? vm.person.personal_info.iban.substring(16,20) : '';
      vm.person.personal_info.iban5 = (vm.person.personal_info.iban !== undefined) ? vm.person.personal_info.iban.substring(20,24) : '';
      vm.editMode=false;
    }
    else {
      vm.editMode=true;
    }


		vm.update=update;
		vm.saving=false;
    vm.showHistoric=false;
    vm.showHistoricFn=showHistoricFn;
		vm.saved=false;
		vm.existRequiredError=existRequiredError;
		vm.showInvalid=false;
    vm.showErrors=false;
    vm.changeModeFn=changeModeFn;
    vm.success = false;

		init();

		function init() {
			vm.person_original = angular.copy(vm.person);
		}

		function update() {
			vm.saved=false;

			if (isValidForm()) {
				vm.saving=true;
				function onUpdateAffiliatesSettingsSuccess(data) {
					vm.saving=false;
					vm.showInvalid=false;
          vm.showErrors=false;
					vm.saved=true;
					vm.dataForm.$dirty=false;
					vm.person_original=vm.person;
					UtilService.setLeavingModal(false);
          vm.editMode=false;
          vm.success=true;
				}
				function onUpdateAffiliatesSettingsError(data) {
					vm.saving=false;
          vm.showErrors=true;
				}

				personDataService.updateProfile(vm.person,{personId: vm.person.id}, onUpdateAffiliatesSettingsSuccess, onUpdateAffiliatesSettingsError);
			}
			else {
				vm.showInvalid=true;
			}
		}
		function isValidForm() {
      // review to refact to simplify or encapsule this validators
      return ( (vm.dataForm.iban.$viewValue && vm.dataForm.iban.$viewValue.length>0) );
		}

		function existRequiredError(value) {
			if (!value || angular.isUndefined(value)) {
				return vm.showInvalid;
			}
			return (value && value.length<1 && vm.showInvalid);
		}

    // Toggle historic pop-up
    function showHistoricFn() {
      vm.showHistoric = !vm.showHistoric;
    }



		//watches
		$scope.$watch('affiliatesSettingsCtrl.person', function (newValue, oldValue) {
			if(newValue) {
				if(!angular.equals(newValue, vm.person_original)) {
					UtilService.setLeavingModal(true);
				} else {
					UtilService.setLeavingModal(false);
				}
			}
		}, true);


    // Binding all IBAN inputs to a one value
    $scope.PersonalInfoIban = {
        iban0: '',
        iban1: '',
        iban2: '',
        iban3: '',
        iban4: '',
        iban5: '',
    };

    // use watch collection to watch properties on the main 'PersonalInfoIban' object
    $scope.$watchCollection('affiliatesSettingsCtrl.person.personal_info', buildIban);

    function buildIban(PersonalInfoIban) {
      // Fill the value with the multiple inputs
      if(PersonalInfoIban.iban0 !== undefined && PersonalInfoIban.iban0 != ''
      && PersonalInfoIban.iban1 !== undefined && PersonalInfoIban.iban1 != ''
      && PersonalInfoIban.iban2 !== undefined && PersonalInfoIban.iban2 != ''
      && PersonalInfoIban.iban3 !== undefined && PersonalInfoIban.iban3 != ''
      && PersonalInfoIban.iban4 !== undefined && PersonalInfoIban.iban4 != ''
      && PersonalInfoIban.iban5 !== undefined && PersonalInfoIban.iban5 != '' )  { // Alert onunload fix
        vm.person.personal_info.iban = PersonalInfoIban.iban0 + PersonalInfoIban.iban1 + PersonalInfoIban.iban2 + PersonalInfoIban.iban3 + PersonalInfoIban.iban4 + PersonalInfoIban.iban5;
      }
    }

    function changeModeFn() {
      vm.editMode=!vm.editMode;
      vm.saved=false;
    }

    // Set iban inputs behaviour
    $scope.keyUpHandler = function(event, nextIdx){
      // 9 -> Tab, 37 -> Left arrow, 39 -> Right arrow,
      if(event.target.value.length >= event.target.attributes.maxlength.value && (event.keyCode != 9 && event.keyCode != 37 && event.keyCode != 39 ) )
        angular.element(document.querySelector('#iban_'+nextIdx))[0].focus();
    }

    // Setting behaviour when paste an IBAN, to fill all inputs
    $scope.pasteIban = function(event) {
      vm.pastedData = String(event.originalEvent.clipboardData.getData('text/plain')).replace(/[\s]/g, ''); // Getting clipboardData

      if(vm.pastedData.length == 24 && vm.editMode) {
        vm.person.personal_info.iban0 = vm.pastedData.substring(0,4);
        vm.person.personal_info.iban1 = vm.pastedData.substring(4,8);
        vm.person.personal_info.iban2 = vm.pastedData.substring(8,12);
        vm.person.personal_info.iban3 = vm.pastedData.substring(12,16);
        vm.person.personal_info.iban4 = vm.pastedData.substring(16,20);
        vm.person.personal_info.iban5 = vm.pastedData.substring(20,24);
      }
    }
	}

	angular
	.module('settings')
	.controller('affiliatesSettingsCtrl', controller);

}());
