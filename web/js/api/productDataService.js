(function () {
	"use strict";

	function productDataService($resource, config) {
		//pub
		this.Product = $resource(config.baseUrl + 'pub/' + config.version + 'products/:idProduct');
		this.Categories = $resource(config.baseUrl + 'pub/' + config.version + 'categories');

		//priv
		this.ProductPriv = $resource(config.baseUrl + 'priv/' + config.version + 'products/:idProduct', {}, {
			'update': {
				method: 'PATCH'
			}
		});
	}

	angular.module('api')
		.service('productDataService', productDataService);

}());