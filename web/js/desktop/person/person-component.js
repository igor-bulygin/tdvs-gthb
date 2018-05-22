(function () {
	"use strict";

	function controller(personDataService, UtilService) {
		var vm = this;
		vm.init = init;
		vm.follow = follow;
		vm.unFollow = unFollow;

		function init(isFollowed) {
			vm.isFollowed = isFollowed;
		}

		function unFollow(personId) {
			function onSetFollowSuccess(data) {
				vm.isFollowed = false;
			}

			function onSetFollowError(err) {
				UtilService.onError(err);
			}

			var params = {
				personId: personId
			}
			personDataService.unFollowPerson(params, params, onSetFollowSuccess, onSetFollowError);
		}

		function follow(personId) {
			function onSetFollowSuccess(data) {
				vm.isFollowed = true;
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