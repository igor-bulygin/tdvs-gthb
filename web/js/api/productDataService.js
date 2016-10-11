(function () {
	"use strict";

	function productDataService($resource, apiConfig) {
		//pub
		this.Product = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'products/:idProduct');
		this.Categories = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'categories');

		//priv
		this.ProductPriv = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'products/:idProduct', {}, {
			'update': {
				method: 'PATCH'
			}
		});
	}

	angular.module('api')
		.service('productDataService', productDataService);

}());