(function () {
	"use strict";
	
	function languageDataService($resource, config) {
		this.Languages = $resource(config.baseUrl + 'pub/' + config.version + 'languages');
	}
	
	angular.module('api')
		.service('languageDataService', languageDataService);
}());