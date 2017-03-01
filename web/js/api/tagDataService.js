(function () {
	"use strict";

	function tagDataService($resource, apiConfig, apiMethods) {
		//pub
		var Tags = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'tag/:idTag');

		//functions
		this.getTags = getTags;

		function getTags(params, onSuccess, onError) {
			apiMethods.get(Tags, params, onSuccess, onError);
		}
	}

	angular.module('api')
		.service('tagDataService', tagDataService);
}());