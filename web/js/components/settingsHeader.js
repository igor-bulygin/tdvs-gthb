(function () {
	"use strict";

	function controller(settingsEvents, $rootScope, $scope, $uibModal) {
		var vm = this;
		vm.saveChanges = saveChanges;
		vm.changesSaved = changesSaved;
		vm.invalidForm = invalidForm;

		function saveChanges() {
			$rootScope.$broadcast(settingsEvents.saveChanges);
		}

		function openModal(template) {
			$uibModal.open({
				templateUrl: template,
				size: 'sm',
				backdrop: false
			})
		}

		function changesSaved(){
			openModal('changesSaved.html');
		}

		function invalidForm(){
			openModal('invalidForm.html');
		}

		$scope.$on(settingsEvents.changesSaved, function(event, args) {
			changesSaved();
		})

		$scope.$on(settingsEvents.invalidForm, function(event, args) {
			invalidForm();
		})
	}


	angular
		.module('todevise')
		.controller('settingsHeaderCtrl', controller);
}());