(function () {
	"use strict";

	function deviserDataService($resource, config) {
		this.Profile = $resource(config.baseUrl + 'priv/' + config.version + 'profile/deviser', {}, {
			'update': {
				method: 'PATCH'
			}
		});

		this.Uploads = config.baseUrl + "priv/" + config.version + 'profile/deviser/uploads';
	}

	angular.module('api')
		.service('deviserDataService', deviserDataService);
}());