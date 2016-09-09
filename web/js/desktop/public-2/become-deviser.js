(function () {
	"use strict";

	function controller(deviserDataService, toastr) {
		var vm = this;
		vm.submitForm = submitForm;
		vm.addUrlPortfolio = addUrlPortfolio;
		vm.addUrlVideo = addUrlVideo;

		function init() {
			vm.invitation = new deviserDataService.InvitationRequest;
			vm.invitation.urls_portfolio = [];
			vm.invitation.urls_video = [];
			addUrlPortfolio();
			addUrlVideo();
		}

		init();

		function submitForm(form) {
			form.$setSubmitted();
			if (form.$valid) {
				console.log(form);
				vm.invitation.$save().then(function (dataSaved) {
					console.log(dataSaved);
					vm.success = true;
				}, function (err) {
					toastr.error("Error saving form!");
					console.log(err);
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


	angular.module('todevise', ['api', 'toastr'])
		.controller('becomeDeviserCtrl', controller);

}());