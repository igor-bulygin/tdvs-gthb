(function () {
	"use strict";

	function controller(deviserDataService, toastr, UtilService, $anchorScroll, $location) {
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


	angular.module('todevise', ['api', 'toastr', 'util', 'header'])
		.controller('becomeDeviserCtrl', controller);

}());