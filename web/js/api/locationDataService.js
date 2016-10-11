(function () {
	"use strict";

	function locationDataService($resource, apiConfig) {
		this.Location = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'locations');
	}

	angular.module('api')
		.service('locationDataService', locationDataService);
}());