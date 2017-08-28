(function () {
	"use strict";

	function orderDataService($resource, apiConfig, apiMethods) {
		//priv
		var orderPack = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'person/:personId/packs');
		var order = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'person/:personId/orders/:orderId');
		var packState = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'person/:personId/packs/:packId/:newState', {}, {
			'update': {
				method: 'PUT'
			}
		});

		//functions
		this.getOrder = getOrder;
		this.getDeviserPack=getDeviserPack;
		this.changePackState=changePackState;

		function getOrder(params, onSuccess, onError) {
			apiMethods.get(order, params, onSuccess, onError);
		}

		function getDeviserPack(params, onSuccess, onError) {
			apiMethods.get(orderPack, params, onSuccess, onError);
		}

		function changePackState(data,params, onSuccess, onError) {
			apiMethods.update(packState, data, params, onSuccess, onError);
		}
	}

	angular
		.module('api')
		.service('orderDataService', orderDataService);
}());