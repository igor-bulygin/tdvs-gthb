(function () {
	"use strict";
	
	function productDataService($resource, config) {
		this.Product = $resource(config.baseUrl + 'pub/' + config.version + 'products/:idProduct');
		this.Categories = $resource(config.baseUrl + 'pub/' + config.version + 'categories');
	}
	
	angular.module('api')
		.service('productDataService', productDataService);
	
}());