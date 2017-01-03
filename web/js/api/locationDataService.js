(function () {
	"use strict";

	function locationDataService($resource, apiConfig) {
		this.Location = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'locations');
		this.Country = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'countries');
	}

	angular.module('api')
		.service('locationDataService', locationDataService);
}());