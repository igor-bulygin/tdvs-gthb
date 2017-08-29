(function () {
	"use strict";

	function controller(storyDataService, UtilService, $window, $uibModal) {
		var vm = this;
		vm.openModal = openModal;
		vm.deleteStory = deleteStory;
		vm.story = angular.copy(story);
		vm.person = angular.copy(person);

		function deleteStory(id) {
			var params = {
				idStory: id
			}

			function onDeleteStorySuccess(data) {
				$window.location.href = vm.person.stories_link;
			}

			storyDataService.deleteStory(params, onDeleteStorySuccess, UtilService.onError);
		}

		function openModal() {
			var modalInstance = $uibModal.open({
				templateUrl: 'deleteStoryModal.html',
				size: 'sm',
				controller: 'deleteStoryModalCtrl',
				controllerAs: 'deleteStoryModalCtrl'
			});

			modalInstance.result.then(function(data) {
				if(data) {
					deleteStory(vm.story.id);
				}
			})
		}
	}

	function deleteModalController($uibModalInstance) {
		var vm = this;
		vm.ok = ok;
		vm.cancel = cancel;

		function ok() {
			$uibModalInstance.close(true);
		}

		function cancel() {
			$uibModalInstance.dismiss(false);
		}

	}

	angular
		.module('person')
		.controller('detailStoryCtrl', controller)
		.controller('deleteStoryModalCtrl', deleteModalController);


}());