(function () {
	"use strict";

	function orderDataService($resource, apiConfig, apiMethods) {
		//priv
		var orderPack = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'person/:personId/packs');
		var order = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'person/:personId/orders/:orderId');

		//functions
		this.getOrder = getOrder;
		this.getDeviserOrders=getDeviserOrders;

		function getOrder(params, onSuccess, onError) {
			apiMethods.get(order, params, onSuccess, onError);
		}

		function getDeviserPack(params, onSuccess, onError) {
			apiMethods.get(orderPack, params, onSuccess, onError);
		}
	}

	angular
		.module('api')
		.service('orderDataService', orderDataService);
}());