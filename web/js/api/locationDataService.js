(function () {
	"use strict";

	function locationDataService($resource, apiConfig, apiMethods) {
		var Location = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'locations');
		var Country = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'countries');
		var WorldWide = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'countries/worldwide');

		this.getCountry = getCountry;
		this.getLocation = getLocation;
		this.getWorldWide = getWorldWide;

		function getCountry(params, onSuccess, onError) {
			apiMethods.get(Country, params, onSuccess, onError);
		}

		function getLocation(params, onSuccess, onError) {
			apiMethods.get(Location, params, onSuccess, onError);
		}

		function getWorldWide(params, onSuccess, onError) {
			apiMethods.get(WorldWide, params, onSuccess, onError);
		}
	}

	angular.module('api')
		.service('locationDataService', locationDataService);
}());