(function () {
	"use strict";

	function orderDataService($resource, apiConfig, apiMethods) {
		//pub
		var Order = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'order/:id');

		//functions
		this.getOrder = getOrder;

		function getOrder(params, onSuccess, onError) {
			apiMethods.get(Order, params, onSuccess, onError);
		}

		function getOrders(params, onSuccess, onError) {
			apiMethods.get(Order, params, onSuccess, onError);
		}
	}

	angular
		.module('api')
		.service('orderDataService', orderDataService);
}());