(function () {
	"use strict";

	function lovedDataService($resource, apiConfig) {
		//priv
		this.LovedPriv = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'loved/:productId');

		//pub
		this.Loved = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'loved/:productId');
	}

	angular
		.module('api')
		.service('lovedDataService', lovedDataService);

}());