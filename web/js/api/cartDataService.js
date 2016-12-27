(function () {
	"use strict";

	function cartDataService($resource, apiConfig) {
		this.Cart = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'cart/:id');
		this.CartProduct = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'cart/:id/product/:productId', null, {
			'update': {
				'method': "PUT"
			}
		});
	}

	angular
		.module('api')
		.service('cartDataService', cartDataService);
}());