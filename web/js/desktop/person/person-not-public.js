(function () {
	"use strict";

	function controller($window, personDataService, UtilService) {
		var vm = this;
		vm.person = person;
		vm.makeProfilePublic = makeProfilePublic;

		function makeProfilePublic() {
			function onUpdateProfileSuccess(returnData) {
				if(returnData.main_link)
					$window.location.href = returnData.main_link;
			}

			var data = {
				account_state: "active"
			}

			personDataService.updateProfile(data, {
				personId: vm.person.short_id
			}, onUpdateProfileSuccess, UtilService.onError);

		}
	}

	angular
		.module('todevise')
		.controller('personNotPublicCtrl', controller);

}());