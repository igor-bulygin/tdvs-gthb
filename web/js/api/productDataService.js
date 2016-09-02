(function () {
	"use strict";
	
	function productDataService($resource, $config) {
		this.Product = $resource(config.baseUrl + 'pub/' + config.version + 'profile/deviser');
	}
	
	angular.module('api')
		.service('productDataService', productDataService);
	
}());