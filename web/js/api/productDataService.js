(function () {
	"use strict";

	function productDataService($resource, apiConfig) {
		//pub
		this.Product = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'products/:idProduct');
		this.PaperType = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'paper-type');
		var Categories = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'categories');

		//priv
		this.ProductPriv = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'products/:idProduct', {}, {
			'update': {
				method: 'PUT'
			}
		});
		this.Uploads = apiConfig.baseUrl + "priv/" + apiConfig.version + 'uploads';

		//functions
		this.getCategories = getCategories;


		function getCategories(params, onsuccess, onerror) {
			Categories.get(params)
				.$promise.then(function(returnData) {
					onsuccess(returnData);
				}, function (err) {
					onerror(err);
				});
		}
	}

	angular.module('api')
		.service('productDataService', productDataService);

}());