(function () {
	"use strict";

	function newsletterDataService($resource, apiConfig, apiMethods) {
		//pub
		var Newsletter = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'newsletter');

		//functions
		this.createNewsletter = createNewsletter;

		function createNewsletter(data, onSuccess, onError) {
			apiMethods.create(Newsletter, data, null, onSuccess, onError);
		}

	}

	angular
		.module('api')
		.service('newsletterDataService', newsletterDataService);

}());