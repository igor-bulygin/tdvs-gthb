(function () {
	"use strict";

	function orderDataService($resource, apiConfig) {
		this.Order = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'order/:id');
	}

	angular
		.module('api')
		.service('orderDataService', orderDataService);
}());