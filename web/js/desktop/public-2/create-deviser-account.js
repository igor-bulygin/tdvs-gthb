(function () {
	"use strict";

	function controller(UtilService, deviserDataService, toastr, $location, $window) {
		var vm = this;
		vm.submitForm = submitForm;
		vm.has_error = UtilService.has_error;
		//get invitation from url
		var invitation_id = $location.absUrl().split('=')[1].split('&')[0];

		function init() {
			deviserDataService.Invitation.get({
				idInvitation: invitation_id
			}).$promise.then(function(dataInvitation) {
				vm.deviser = {
					invitation_id: invitation_id,
					email: dataInvitation.email
				}
			});
			
		}

		init();

		function submitForm(form) {
			if (form.password_confirm.$error.same)
				form.$setValidity('password_confirm', false);
			if (form.$valid) {
				form.$setSubmitted();
				vm.new_deviser = new deviserDataService.Devisers;
				for(var key in vm.deviser) {
					vm.new_deviser[key] = vm.deviser[key];
				}
				vm.new_deviser.$save().then(function (dataSaved) {
					$window.location.href= '/deviser/' + dataSaved.slug + '/' + dataSaved.id + '/about/edit';
				}, function (err) {
					toastr.error("Error saving form!");
				})
			} else {
				toastr.error("Invalid form!")
			}
		}

	}

	angular
		.module('todevise', ['api', 'util', 'toastr'])
		.controller('createDeviserCtrl', controller);

}());