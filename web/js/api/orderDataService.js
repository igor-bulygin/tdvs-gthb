(function () {
	"use strict";

	function orderDataService($resource, apiConfig) {
		//pub
		var Order = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'order/:id');

		//functions
		this.getOrder = getOrder;

		function getOrder(params, onsuccess, onerror) {
			Order.get(params)
				.$promise.then(function(returnData) {
					onsuccess(returnData);
				}, function (err) {
					onerror(err);
				})
		}
	}

	angular
		.module('api')
		.service('orderDataService', orderDataService);
}());