(function () {
	"use strict";
	
	function productDataService($resource, config) {
		this.Product = $resource(config.baseUrl + 'pub/' + config.version + 'products/:idProduct');
	}
	
	angular.module('api')
		.service('productDataService', productDataService);
	
}());