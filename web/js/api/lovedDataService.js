(function () {
	"use strict";

	function lovedDataService($resource, apiConfig) {
		//priv
		this.LovedPriv = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'loved/:lovedId');

		//pub
		this.Loved = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'loved/:lovedId');
	}

	angular
		.module('api')
		.service('lovedDataService', lovedDataService);

}());