(function () {
	"use strict";

	function boxDataService($resource, apiConfig, apiMethods) {
		//pub
        /**
         * endpoint to count products based on GET params
         */
        var BoxesCount = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'box/count');
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
		this.getBoxesCount = getBoxesCount;

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
        /**
         * @param params - GET params
         * @returns Promise for use in Promise.all
         */
        function getBoxesCount(params) {
            return apiMethods.getUnresolved(BoxesCount, params);
        }


	}

	angular.module('api')
		.service('boxDataService', boxDataService);
	
}());

