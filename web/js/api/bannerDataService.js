(function () {
	"use strict";
	
	function bannerDataService($resource, apiConfig, apiMethods) {
		var Banner = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'banner/:id', null, {
			'update': {
				'method': 'PATCH'
			}
		});

		//functions
		this.getBanners = getBanners;
		this.createBanner = createBanner;
		this.updateBanner = updateBanner;
		this.deleteBanner = deleteBanner;

		function getBanners(params, onSuccess, onError) {
			apiMethods.get(Banner, params, onSuccess, onError);
		}

		function createBanner(data, onSuccess, onError) {
			apiMethods.create(Banner, data, null, onSuccess, onError)
		}

		function updateBanner(data, params, onSuccess, onError) {
			apiMethods.update(Banner, data, params, onSuccess, onError);
		}

		function deleteBanner(params, onSuccess, onError) {
			apiMethods.deleteItem(Banner, params, onSuccess, onError);
		}
	}
	
	angular.module('api')
		.service('bannerDataService', bannerDataService);
}());