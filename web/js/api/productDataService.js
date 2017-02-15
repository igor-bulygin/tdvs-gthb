(function () {
	"use strict";

	function productDataService($resource, apiConfig) {
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
		this.Uploads = apiConfig.baseUrl + "priv/" + apiConfig.version + 'uploads';

		//functions
		this.getProductPub = getProductPub;
		this.getPaperType = getPaperType;
		this.getCategories = getCategories;
		this.getProductPriv = getProductPriv;
		this.postProductPriv = postProductPriv;
		this.updateProductPriv = updateProductPriv;
		this.deleteProductPriv = deleteProductPriv;

		function getProductPub(params, onsuccess, onerror) {
			Product.get(params)
				.$promise.then(function (returnData) {
					onsuccess(returnData);
				}, function (err) {
					onerror(err);
				})
		}

		function getPaperType(onsuccess, onerror) {
			PaperType.get()
				.$promise.then(function (returnData) {
					onsuccess(returnData);
				}, function (err) {
					onerror(err);
				})
		}

		function getCategories(params, onsuccess, onerror) {
			Categories.get(params)
				.$promise.then(function(returnData) {
					onsuccess(returnData);
				}, function (err) {
					onerror(err);
				});
		}

		function getProductPriv(params, onsuccess, onerror) {
			ProductPriv.get(params)
				.$promise.then(function(returnData) {
					onsuccess(returnData);
				}, function(err) {
					onerror(err);
				});
		}

		function postProductPriv(data, onsuccess, onerror) {
			var new_product = new ProductPriv;
			for(var key in data) {
				new_product[key] = angular.copy(data[key]);
			}
			new_product.$save()
				.then(function(returnData) {
					onsuccess(returnData);
				}, function(err) {
					onerror(err);
				})
		}

		function updateProductPriv(data, params, onsuccess, onerror) {
			var update_product = new ProductPriv;
			for(var key in data) {
				update_product[key] = angular.copy(data[key])
			}
			update_product.$update(params)
				.then(function(returnData) {
					onsuccess(returnData);
				}, function(err) {
					onerror(err);
				})
		}

		function deleteProductPriv(params, onsuccess, onerror) {
			ProductPriv.delete(params)
				.$promise.then(function(returnData) {
					onsuccess(returnData);
				}, function(err) {
					onerror(err);
				});
		}
	}

	angular.module('api')
		.service('productDataService', productDataService);

}());