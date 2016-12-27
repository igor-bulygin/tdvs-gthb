(function () {
	"use strict";

	function cartDataService($resource, apiConfig) {
		this.Cart = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'cart/');
	}

	angular
		.module('api')
		.service('cartDataService', cartDataService);
}());