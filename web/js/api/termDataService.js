(function () {
	"use strict";

	function termDataService($resource) {
		this.adminTerm = $resource(config.baseUrl + 'admin' + config.version + 'term/');
	}

	angular.module('api')
		.service('termDataService', termDataService);

}());