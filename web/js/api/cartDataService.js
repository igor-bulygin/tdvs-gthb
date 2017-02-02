(function () {
	"use strict";

	function cartDataService($resource, apiConfig) {
		this.Cart = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'cart/:id');
		this.CartProduct = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'cart/:id/product/:productId', null, {
			'update': {
				'method': "PUT"
			}
		});
		this.CartClientInfo = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'cart/:id/clientInfo');
		this.CartReceiveToken = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'cart/:cartId/receiveToken');
	}

	angular
		.module('api')
		.service('cartDataService', cartDataService);
}());