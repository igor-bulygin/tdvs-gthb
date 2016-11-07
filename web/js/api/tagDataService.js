(function () {
	"use strict";

	function tagDataService($resource, apiConfig) {
		//pub
		this.Tags = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'tag/:idTag');
	}

	angular.module('api')
		.service('tagDataService', tagDataService);
}());