(function () {
	"use strict";

	function controller(deviserDataService, UtilService, toastr) {
		var vm = this;
		vm.active = active;

		function active() {
			var patch = new deviserDataService.Profile;
			patch.deviser_id = UtilService.returnDeviserIdFromUrl();
			patch.scenario = "deviser-update-profile";
			patch.account_state = "active";
			patch.$update().then(function (updateData) {
				toastr.error("ok");
				toastr.error("ok");
				console.log(updateData);
			}, function (err) {
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