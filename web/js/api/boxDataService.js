(function () {
	"use strict";

	function boxDataService($resource, apiConfig, apiMethods) {
		//pub
		var Box = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'box/:idBox');
		//priv
		var BoxPriv = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'box/:idBox', null, {
			'update': {
				method: 'PATCH'
			}
		});
		var BoxProduct = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'box/:idBox/product/:idProduct');

		//functions
		this.getBoxPub = getBoxPub;
		this.getBoxPriv = getBoxPriv;
		this.createBox = createBox;
		this.updateBox = updateBox;
		this.deleteBox = deleteBox;
		this.addProduct = addProduct;
		this.deleteProduct = deleteProduct;

		function getBoxPub(params, onSuccess, onError) {
			apiMethods.get(Box, params, onSuccess, onError);
		}

		function getBoxPriv(params, onSuccess, onError) {
			apiMethods.get(BoxPriv, params, onSuccess, onError);
		}

		function createBox(data, onSuccess, onError) {
			apiMethods.create(BoxPriv, data, null, onSuccess, onError)
		}

		function updateBox(data, params, onSuccess, onError) {
			apiMethods.update(BoxPriv, data, params, onSuccess, onError);
		}

		function deleteBox(params, onSuccess, onError) {
			apiMethods.deleteItem(BoxPriv, params, onSuccess, onError);
		}

		function addProduct(data, params, onSuccess, onError) {
			apiMethods.create(BoxProduct, data, params, onSuccess, onError);
		}

		function deleteProduct(params, onSuccess, onError) {
			apiMethods.deleteItem(BoxProduct, params, onSuccess, onError);
		}

	}

	angular.module('api')
		.service('boxDataService', boxDataService);
	
}());

