(function () {
	"use strict";

	function lovedDataService($resource, apiConfig, apiMethods) {
		//priv
		var LovedPriv = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'loved/:productId');
		//pub
		var Loved = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'loved/:productId');

		this.setLoved = setLoved;
		this.deleteLoved = deleteLoved;

		function setLoved(data, onSuccess, onError) {
			apiMethods.create(LovedPriv, data, null, onSuccess, onError);
		}

		function deleteLoved(params, onSuccess, onError) {
			apiMethods.deleteItem(LovedPriv, params, onSuccess, onError)
		}
	}

	angular
		.module('api')
		.service('lovedDataService', lovedDataService);

}());