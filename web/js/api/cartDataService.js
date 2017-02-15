(function () {
	"use strict";

	function cartDataService($resource, apiConfig) {
		//resources
		//pub
		var Cart = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'cart/:id');
		var CartProduct = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'cart/:id/product/:productId', null, {
			'update': {
				'method': "PUT"
			}
		});
		var CartClientInfo = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'cart/:id/clientInfo');
		var CartReceiveToken = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'cart/:cartId/receiveToken');

		//functions
		this.createCart = createCart;
		this.getCart = getCart;
		this.deleteItem = deleteItem;
		this.saveUserInfo = saveUserInfo;
		this.getCartToken = getCartToken;
		this.addProduct = addProduct;

		function createCart(data, onsuccess, onerror) {
			Cart.save()
				.$promise.then(function(returnData) {
					onsuccess(returnData);
				}, function(err) {
					onerror(err);
				});
		}

		function getCart(data, onsuccess, onerror) {
			Cart.get(data)
				.$promise.then(function(returnData) {
					onsuccess(returnData);
				}, function(err) {
					onerror(err);
				});
		}

		function deleteItem(data, onsuccess, onerror) {
			CartProduct.delete(data)
				.$promise.then(function(returnData) {
					onsuccess(returnData);
				}, function(err) {
					onerror(err);
				});
		}

		function saveUserInfo(data, params, onsuccess, onerror) {
			var client_info = new CartClientInfo;
			for(var key in data) {
				client_info[key] = angular.copy(data[key]);
			}
			client_info.$save(params)
				.then(function(returnData) {
					onsuccess(returnData);
				}, function (err) {
					onerror(err);
				});
		}

		function getCartToken(data, params, onsuccess, onerror) {
			var cartToken = new CartReceiveToken;
			for(var key in data) {
				cartToken[key] = angular.copy(data[key]);
			}
			cartToken.$save(params)
				.then(function(returnData) {
					onsuccess(returnData);
				}, function (err) {
					onerror(err);
				})
		}

		function addProduct(data, params, onsuccess, onerror) {
			var newProduct = new CartProduct;
			for(var key in data) {
				newProduct[key] = angular.copy(data[key]);
			}
			newProduct.$save(params)
				.then(function(returnData){
					onsuccess(returnData);
				}, function(err){
					onerror(err);
				})
		}


	}

	angular
		.module('api')
		.service('cartDataService', cartDataService);
}());