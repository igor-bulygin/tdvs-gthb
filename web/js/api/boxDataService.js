(function () {
	"use strict";

	function boxDataService($resource, apiConfig) {
		//pub
		var Box = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'box/:idBox');
		//priv
		var BoxPriv = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'box/:idBox');
		var BoxProduct = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'box/:idBox/product');

		//functions
		this.getBoxPub = getBoxPub;
		this.getBoxPriv = getBoxPriv;
		this.createBox = createBox;
		this.deleteBox = deleteBox;
		this.addProduct = addProduct;
		this.deleteProduct = deleteProduct;

		function getBoxPub(params, onsuccess, onerror) {
			Box.get(params)
				.$promise.then(function(returnData) {
					onsuccess(returnData);
				}, function(err) {
					onerror(err);
				});
		}

		function getBoxPriv(params, onsuccess, onerror) {
			BoxPriv.get(params)
				.$promise.then(function (returnData) {
					onsuccess(returnData);
				}, function(err) {
					onerror(err);
				})
		}

		function createBox(data, onsuccess, onerror) {
			var newBox = new BoxPriv;
			for(var key in data) {
				newBox[key] = angular.copy(data[key]);
			}
			newBox.$save().then(function(returnData) {
				onsuccess(returnData);
			}, function (err) {
				onerror(err);
			});
		}

		function deleteBox(params, onsuccess, onerror) {
			BoxPriv.delete(params)
				.$promise.then(function (returnData) {
					onsuccess(returnData);
				}, function(err) {
					onerror(err);
				});
		}

		function addProduct(data, params, onsuccess, onerror) {
			var newProduct = new BoxProduct;
			for(var key in data) {
				newProduct[key] = data[key];
			}
			newProduct.$save(params)
				.then(function(returnData) {
					onsuccess(returnData);
				}, function(err) {
					onerror(err);
				});
		}

		function deleteProduct(params, onsuccess, onerror) {
			BoxProduct.delete(params)
				.$promise.then(function (returnData) {
					onsuccess(returnData);
				}, function (err) {
					onerror(err);
				});
		}

	}

	angular.module('api')
		.service('boxDataService', boxDataService);
	
}());

