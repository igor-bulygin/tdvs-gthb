(function () {
	"use strict";

	function locationDataService($resource, apiConfig, apiMethods) {
		var Location = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'locations');
		var Country = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'countries');

		this.getCountry = getCountry;
		this.getLocation = getLocation;

		function getCountry(params, onSuccess, onError) {
			apiMethods.get(Country, params, onSuccess, onError);
		}

		function getLocation(params, onSuccess, onError) {
			apiMethods.get(Location, params, onSuccess, onError);
		}
	}

	angular.module('api')
		.service('locationDataService', locationDataService);
}());