(function () {
	"use strict";

	function controller(personDataService, UtilService) {
		var vm = this;
		vm.unFollow = unFollow;
		vm.person = person;

		function unFollow(person) {
			function onSetFollowSuccess(data) {
					person.is_followed = !person.is_followed;
				}
				function onSetFollowError(err) {
					UtilService.onError(err);
				}
			var params = {
				personId: person.id
			}
				personDataService.unFollowPerson(params, params, onSetFollowSuccess, onSetFollowError);
		}

		function follow(personId) {
			function onSetFollowSuccess(data) {
					// person.is_followed = !person.is_followed;
				}
				function onSetFollowError(err) {
					UtilService.onError(err);
				}
			var params = {
				personId: personId
			}
				personDataService.followPerson(params, params, onSetFollowSuccess, onSetFollowError);
		}

	}

	angular
		.module('person')
		.controller('personComponentCtrl', controller)

}());