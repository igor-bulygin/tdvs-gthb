(function () {
	"use strict";

	function controller(deviserDataService, toastr, UtilService) {
		var vm = this;
		vm.submitForm = submitForm;
		vm.addUrlPortfolio = addUrlPortfolio;
		vm.addUrlVideo = addUrlVideo;
		vm.has_error = UtilService.has_error;

		function init() {
			vm.invitation = {
				urls_portfolio: [],
				urls_video: []
			}
			addUrlPortfolio();
			addUrlVideo();
		}

		init();

		function submitForm(form) {
			form.$setSubmitted();
			if (form.$valid) {
				vm.new_invitation = new deviserDataService.InvitationRequest;
				for(var key in vm.invitation) {
					vm.new_invitation[key] = vm.invitation[key];
				}
				vm.new_invitation.$save().then(function (dataSaved) {
					vm.success = true;
					init();
					vm.form.$setPristine();
					vm.form.$setUntouched();
				}, function (err) {
					toastr.error("Error saving form!");
				})
			} else {
				toastr.error("Invalid form!");
			}
		}

		function addUrlPortfolio() {
			if (vm.invitation.urls_portfolio[vm.invitation.urls_portfolio.length - 1] !== null)
				vm.invitation.urls_portfolio.push(null);
		}

		function addUrlVideo() {
			if (vm.invitation.urls_video[vm.invitation.urls_video.length - 1] !== null)
				vm.invitation.urls_video.push(null);
		}

	}


	angular.module('todevise', ['api', 'toastr', 'util'])
		.controller('becomeDeviserCtrl', controller);

}());