(function () {
	"use strict";

	function termDataService($resource, apiConfig) {
		this.adminTerm = $resource(apiConfig.baseUrl + 'admin' + apiConfig.version + 'term/');
	}

	angular.module('api')
		.service('termDataService', termDataService);

}());