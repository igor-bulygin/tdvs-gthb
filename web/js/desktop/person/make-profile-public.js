(function () {
	"use strict";

	function controller(personDataService, UtilService, toastr, $window, $rootScope, deviserEvents) {
		var vm = this;
		vm.active = active;

		function active() {
			function onUpdateProfileSuccess(data) {
				$window.location.href = '/deviser/' + data.slug + '/' + data.id + '/store/edit';
			}

			function onUpdateProfileError(err) {
				vm.errorsRequired = true;
				$rootScope.$broadcast(deviserEvents.make_profile_public_errors, {required_fields: err.data.errors.required});
				for (var key in err.data.errors) {
					console.log(err.data.errors[key]);
				}
			}

			var data = {
				account_state: "active"
			}

			personDataService.updateProfile(data, {
				personId: person.short_id
			}, onUpdateProfileSuccess, onUpdateProfileError);
		}
	}

	angular
		.module('todevise')
		.controller('makeProfilePublicCtrl', controller);

}());