(function () {
	"use strict";

	function controller(deviserDataService, UtilService, toastr, $window, $rootScope, deviserEvents) {
		var vm = this;
		vm.active = active;

		function active() {
			var patch = new deviserDataService.Profile;
			patch.deviser_id = UtilService.returnDeviserIdFromUrl();
			patch.scenario = "deviser-update-profile";
			patch.account_state = "active";
			patch.$update().then(function (updateData) {
				$window.location.href = '/deviser/' + updateData.slug + '/' + updateData.id + '/about/edit';
			}, function (err) {
				$rootScope.$broadcast(deviserEvents.make_profile_public_errors, {required_fields: err.data.errors.required});
				for (var key in err.data.errors) {
					toastr.error(err.data.errors[key]);
				}
			});
		}
	}

	angular
		.module('todevise')
		.controller('makeProfilePublicCtrl', controller);

}());