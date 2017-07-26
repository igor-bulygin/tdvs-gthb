(function () {
	"use strict";

	function orderDataService($resource, apiConfig, apiMethods) {
		//pub
		var Order = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'order/:id');
		//priv
		var orderPriv = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'person/:personId/packs');

		//functions
		this.getOrder = getOrder;
		this.getDeviserOrders=getDeviserOrders;

		function getOrder(params, onSuccess, onError) {
			apiMethods.get(Order, params, onSuccess, onError);
		}

		function getDeviserOrders(params, onSuccess, onError) {
			apiMethods.get(orderPriv, params, onSuccess, onError);
		}
	}

	angular
		.module('api')
		.service('orderDataService', orderDataService);
}());