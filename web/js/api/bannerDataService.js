(function () {
	"use strict";
	
	function bannerDataService($resource, apiConfig, apiMethods) {
		var Banner = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'banner');

		//functions
		this.getBanners = getBanners;
		this.createBanner = createBanner;

		function getBanners(params, onSuccess, onError) {
			apiMethods.get(Banner, params, onSuccess, onError);
		}

		function createBanner(data, onSuccess, onError) {
			apiMethods.create(Banner, data, null, onSuccess, onError)
		}
	}
	
	angular.module('api')
		.service('bannerDataService', bannerDataService);
}());