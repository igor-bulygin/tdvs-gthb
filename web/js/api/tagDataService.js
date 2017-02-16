(function () {
	"use strict";

	function tagDataService($resource, apiConfig) {
		//pub
		var Tags = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'tag/:idTag');

		//functions
		this.getTags = getTags;

		function getTags(onsuccess, onerror) {
			Tags.get()
				.$promise.then(function(returnData) {
					onsuccess(returnData);
				}, function(err) {
					onerror(err);
				});
		}
	}

	angular.module('api')
		.service('tagDataService', tagDataService);
}());