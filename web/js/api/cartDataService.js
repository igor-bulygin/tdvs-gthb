(function () {
	"use strict";

	function cartDataService($resource, apiConfig, apiMethods) {
		//resources
		//pub
		var Cart = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'cart/:id');
		var CartProduct = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'cart/:id/product/:productId', null, {
			'update': {
				'method': "PUT"
			}
		});
		var CartReceiveToken = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'cart/:cartId/receiveToken');

		//functions
		this.createCart = createCart;
		this.getCart = getCart;
		this.deleteItem = deleteItem;
		this.getCartToken = getCartToken;
		this.addProduct = addProduct;

		function createCart(onSuccess, onError) {
			apiMethods.create(Cart, null, null, onSuccess, onError);
		}

		function getCart(params, onSuccess, onError) {
			apiMethods.get(Cart, params, onSuccess, onError);
		}

		function deleteItem(params, onSuccess, onError) {
			apiMethods.deleteItem(CartProduct, params, onSuccess, onError);
		}

		function getCartToken(data, params, onSuccess, onError) {
			apiMethods.create(CartReceiveToken, data, params, onSuccess, onError);
		}

		function addProduct(data, params, onSuccess, onError) {
			apiMethods.create(CartProduct, data, params, onSuccess, onError);
		}


	}

	angular
		.module('api')
		.service('cartDataService', cartDataService);
}());