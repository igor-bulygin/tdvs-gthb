(function () {
	"use strict";

	function controller(invitationDataService, toastr, UtilService, $anchorScroll, $location) {
		var vm = this;
		vm.submitForm = submitForm;
		vm.addUrlPortfolio = addUrlPortfolio;
		vm.addUrlVideo = addUrlVideo;
		vm.scrollToForm = scrollToForm;
		vm.splicePortfolio = splicePortfolio;
		vm.spliceVideos = spliceVideos;
		vm.urlRegEx = UtilService.urlRegEx;
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

		function scrollToForm() {
			$location.hash('form');
			$anchorScroll();
		}

		function submitForm(form) {
			function onCreateInvitationRequestSuccess(data) {
				vm.success = true;
				init();
				vm.form.$setPristine();
				vm.form.$setUntouched();
			}

			form.$setSubmitted();
			if (form.$valid) {
				invitationDataService.createInvitationRequest(vm.invitation, null, onCreateInvitationRequestSuccess, UtilService.onError)
			}
		}

		function addUrlPortfolio() {
			if (vm.invitation.urls_portfolio[vm.invitation.urls_portfolio.length - 1] !== null) {
				if(vm.invitation.urls_portfolio.length > 0) {
				 	if(!vm.form['portfolio_'+(vm.invitation.urls_portfolio.length - 1)].$invalid)
						vm.invitation.urls_portfolio.push(null);
				} else {
					vm.invitation.urls_portfolio.push(null);
				}
			}
		}

		function addUrlVideo() {
			if (vm.invitation.urls_video[vm.invitation.urls_video.length - 1] !== null) {
				if(vm.invitation.urls_video.length > 0) {
					if(!vm.form['video_'+(vm.invitation.urls_video.length - 1)].$invalid)
						vm.invitation.urls_video.push(null);
				} else {
						vm.invitation.urls_video.push(null);
				}
			}
		}

		function splicePortfolio(index) {
			vm.invitation.urls_portfolio.splice(index, 1);
		}

		function spliceVideos(index) {
			vm.invitation.urls_video.splice(index, 1);
		}

	}


	angular.module('todevise')
		.controller('becomeDeviserCtrl', controller);

}());