(function () {
	"use strict";

	function directive() {
		return {
			templateUrl: currentHost() + '/js/util/form-messages/form-messages.html',
			scope: {
				field: '='
			}
		};
	}

	angular
		.module('util.formMessages', ['ngMessages'])
		.directive('formMessages', directive);

}());