(function () {
	"use strict";

	function controller($window, personDataService, UtilService) {
		var vm = this;
		vm.person = person;
		vm.makeProfilePublic = makeProfilePublic;

		function makeProfilePublic(){
			function onUpdateProfileSuccess() {
				if(data.main_link)
					$window.location.href = data.main_link
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
		.controller('deviserNotPublicCtrl', controller);

}());