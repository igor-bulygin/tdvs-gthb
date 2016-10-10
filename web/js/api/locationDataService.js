(function () {
	"use strict";

	function locationDataService($resource, config) {
		this.Location = $resource(config.baseUrl + 'pub/' + config.version + 'locations');
	}

	angular.module('api')
		.service('locationDataService', locationDataService);
}());