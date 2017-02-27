(function () {
	"use strict";
	
	function languageDataService($resource, apiConfig, apiMethods) {
		var Languages = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'languages');

		//functions
		this.getLanguages = getLanguages;

		function getLanguages(onSuccess, onError) {
			apiMethods.get(Languages, null, onSuccess, onError)
		}
	}
	
	angular.module('api')
		.service('languageDataService', languageDataService);
}());