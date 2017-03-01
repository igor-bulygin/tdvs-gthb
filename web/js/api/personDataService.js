(function () {
	"use strict";

	function personDataService($resource, apiConfig, apiMethods) {
		//pub
		var Person = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'person');
		var Login = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'auth/login');
		var Logout = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'auth/logout');

		//priv
		var Profile = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'profile/person', {}, {
			'update': {
				method: 'PATCH'
			}
		})
		var Upload = apiConfig.baseUrl + 'priv' + apiConfig.version + 'uploads';


		this.createDeviser = createDeviser;

		function createDeviser(data, params, onSuccess, onError) {
			data = Object.assign(data, {type: [2]});
			apiMethods.create(Person, data, params, onSuccess, onError)
		}

	}

	angular
		.module('api')
		.service('personDataService', personDataService);

}());