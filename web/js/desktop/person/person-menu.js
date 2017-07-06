(function () {
	"use strict";

	function controller($scope, deviserEvents) {
		var vm = this;
		vm.required = {};

		function setErrors(errors_array) {
			errors_array.forEach((element) => vm.required[element] = true)
		}

		//events
		$scope.$on(deviserEvents.make_profile_public_errors, function(event, args) {
			if(angular.isArray(args.required_sections) && args.required_sections.length > 0) {
				setErrors(args.required_sections)
			}
		})


	}

	angular
		.module('person')
		.controller('personMenuCtrl', controller);

}());