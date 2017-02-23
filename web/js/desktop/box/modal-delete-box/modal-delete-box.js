(function () {
	"use strict";

	function controller(boxDataService, UtilService, $window) {
		var vm = this;
		vm.deleteBox = deleteBox;

		function deleteBox(id) {
			function onDeleteBoxSuccess(data) {
				vm.close();
				$window.location.href= currentHost() + "/deviser/" + vm.resolve.deviser.slug + "/" + vm.resolve.deviser.id + '/boxes';
			}

			boxDataService.deleteBox({
				idBox: vm.resolve.boxId
			}, onDeleteBoxSuccess, UtilService.onError);
		}
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/box/modal-delete-box/modal-delete-box.html',
		controller: controller,
		controllerAs: 'modalDeleteBoxCtrl',
		bindings: {
			resolve: '<',
			dismiss: '&',
			close: '&'
		}
	}

	angular.module('box')
		.component('modalDeleteBox', component);

}());