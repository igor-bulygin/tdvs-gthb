(function () {
	"use strict";

	function controller(personDataService, UtilService, toastr, $window, $rootScope, deviserEvents) {
		var vm = this;
		vm.active = active;
		vm.type = person.type[0];

		function active() {
			function onUpdateProfileSuccess(data) {
				if (data.store_edit_link) {
					$window.location.href = data.store_edit_link;
				} else {
					$window.location.href = data.about_link;
				}
			}

			function onUpdateProfileError(err) {
				vm.errorsRequired = true;
				$rootScope.$broadcast(deviserEvents.make_profile_public_errors, 
						{required_fields: err.data.errors.required, required_sections: err.data.errors.sections});
				/*for (var key in err.data.errors) {
					UtilService.onError(err.data.errors[key]);
				}*/
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
		.module('person')
		.controller('makeProfilePublicCtrl', controller);

}());