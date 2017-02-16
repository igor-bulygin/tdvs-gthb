(function () {
	"use strict";
	
	function languageDataService($resource, apiConfig) {
		var Languages = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'languages');

		//functions
		this.getLanguages = getLanguages;

		function getLanguages(onsuccess, onerror) {
			Languages.get()
				.$promise.then(function (returnData) {
					onsuccess(returnData);
				}, function (err) {
					onerror(err);
				});
		}
	}
	
	angular.module('api')
		.service('languageDataService', languageDataService);
}());