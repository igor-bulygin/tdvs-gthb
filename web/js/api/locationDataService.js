(function () {
	"use strict";

	function locationDataService($resource, apiConfig, apiMethods) {
		var Location = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'locations');
		var Country = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'countries/:countryCode');
		var WorldWide = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'countries/worldwide');
		var ShippingCountry = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'countries/shipping', {}, {
			'get': {
				method: 'GET',
				isArray: true
			}
		});

		this.getCountry = getCountry;
		this.getLocation = getLocation;
		this.getWorldWide = getWorldWide;
		this.getShippingCountries= getShippingCountries;

		function getCountry(params, onSuccess, onError) {
			apiMethods.get(Country, params, onSuccess, onError);
		}

		function getLocation(params, onSuccess, onError) {
			apiMethods.get(Location, params, onSuccess, onError);
		}

		function getWorldWide(params, onSuccess, onError) {
			apiMethods.get(WorldWide, params, onSuccess, onError);
		}

		function getShippingCountries(params, onSuccess, onError) {
			apiMethods.get(ShippingCountry, params, onSuccess, onError);
		}
	}

	angular.module('api')
		.service('locationDataService', locationDataService);
}());