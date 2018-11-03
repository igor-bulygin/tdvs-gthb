(function () {
	"use strict";

	function productDataService($resource, apiConfig, apiMethods) {
		//pub
		var Product = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'products/:idProduct');
		var PaperType = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'paper-type');
		var Categories = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'categories');
		var Products = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'products');
        /**
         * endpoint to count products based on GET params
         */
        var ProductsCount = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'products/count');
		//priv
		var ProductPriv = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'products/:idProduct', {}, {
			'update': {
				method: 'PUT'
			}
		});

		//functions		
		this.getProducts = getProducts;
		this.getProductPub = getProductPub;
		this.getPaperType = getPaperType;
		this.getCategories = getCategories;
		this.getProductPriv = getProductPriv;
		this.postProductPriv = postProductPriv;
		this.updateProductPriv = updateProductPriv;
		this.deleteProductPriv = deleteProductPriv;
		this.getProductsCount = getProductsCount;

		function getProducts(params, onSuccess, onError) {
			apiMethods.get(Products, params, onSuccess, onError);
		}

		function getProductPub(params, onSuccess, onError) {
			apiMethods.get(Product, params, onSuccess, onError);
		}

		function getPaperType(params, onSuccess, onError) {
			apiMethods.get(PaperType, params, onSuccess, onError);
		}

		function getCategories(params, onSuccess, onError) {
			apiMethods.get(Categories, params, onSuccess, onError);
		}

		function getProductPriv(params, onSuccess, onError) {
			apiMethods.get(ProductPriv, params, onSuccess, onError);
		}

		function postProductPriv(data, params, onSuccess, onError) {
			apiMethods.create(ProductPriv, data, params, onSuccess, onError);
		}

		function updateProductPriv(data, params, onSuccess, onError) {
			apiMethods.update(ProductPriv, data, params, onSuccess, onError);
		}

		function deleteProductPriv(params, onSuccess, onError) {
			apiMethods.deleteItem(ProductPriv, params, onSuccess, onError);
		}

        /**
         * @param params - GET params
         * @returns Promise for use in Promise.all
         */
        function getProductsCount(params) {
            return apiMethods.getUnresolved(ProductsCount, params);
        }
	}

	angular.module('api')
		.service('productDataService', productDataService);

}());