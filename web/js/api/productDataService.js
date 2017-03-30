(function () {
	"use strict";

	function productDataService($resource, apiConfig, apiMethods, Upload) {
		//pub
		var Product = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'products/:idProduct');
		var PaperType = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'paper-type');
		var Categories = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'categories');

		//priv
		var ProductPriv = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'products/:idProduct', {}, {
			'update': {
				method: 'PUT'
			}
		});
		var Uploads = apiConfig.baseUrl + "priv/" + apiConfig.version + 'uploads';

		//functions
		this.getProductPub = getProductPub;
		this.getPaperType = getPaperType;
		this.getCategories = getCategories;
		this.getProductPriv = getProductPriv;
		this.postProductPriv = postProductPriv;
		this.updateProductPriv = updateProductPriv;
		this.deleteProductPriv = deleteProductPriv;
		this.UploadFile = UploadFile;

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

		function UploadFile(data, onSuccess, onError, onUploading) {
			Upload.upload({
				url: Uploads,
				data: data
			}).then(function(returnData) { onSuccess(returnData)}, function(err) {onError(err);}, function(evt) { onUploading(evt)} );
		}
	}

	angular.module('api')
		.service('productDataService', productDataService);

}());