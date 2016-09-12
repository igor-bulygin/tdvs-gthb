(function () {
	"use strict";

	function directive() {
		return {
			restrict: 'E',
			scope: {
				condition: '=',
				field: '='
			},
			templateUrl: currentHost() + '/js/util/form-errors/form-errors.html'
		}
	}

	angular
		.module('util')
		.directive('formErrors', directive)

}());