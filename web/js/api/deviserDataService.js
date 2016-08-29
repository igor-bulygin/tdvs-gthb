(function () {
	"use strict";
	
	function deviserDataService($resource, config) {
		this.Profile = $resource(config.baseUrl + 'priv/' + config.version + 'profile/deviser', {}, {
			'update': {
				method: 'PATCH'
			}
		});
	}
	
	angular.module('api')
		.service('deviserDataService', deviserDataService);
}());