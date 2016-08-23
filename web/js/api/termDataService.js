(function () {
	"use strict";

	function termDataService($resource) {
		this.adminTerm = $resource(currentHost() + '/api3/admin/v1/term/');
	}

	angular.module('api')
		.service('termDataService', termDataService);

}());