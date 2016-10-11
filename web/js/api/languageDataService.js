(function () {
	"use strict";
	
	function languageDataService($resource, apiConfig) {
		this.Languages = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'languages');
	}
	
	angular.module('api')
		.service('languageDataService', languageDataService);
}());