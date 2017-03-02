(function () {
	"use strict";

	function personDataService($resource, apiConfig, apiMethods) {
		//pub
		var Person = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'person');
		var Login = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'auth/login');
		var Logout = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'auth/logout');

		//priv
		var Profile = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'person/:personId', {}, {
			'update': {
				method: 'PATCH'
			}
		});
		this.Uploads = apiConfig.baseUrl + 'priv/' + apiConfig.version + 'uploads';

		//methods
		this.createDeviser = createDeviser;
		this.createInfluencer = createInfluencer;
		this.login = login;
		this.logout = logout;
		this.getProfile = getProfile;
		this.updateProfile = updateProfile;

		function createDeviser(data, params, onSuccess, onError) {
			data = Object.assign(data, {type: [2]});
			apiMethods.create(Person, data, params, onSuccess, onError);
		}

		function createInfluencer(data, params, onSuccess, onError) {
			data = Object.assign(data, {type: [3]});
			apiMethods.create(Person, data, params, onSuccess, onError);
		}

		function login(data, params, onSuccess, onError) {
			apiMethods.create(Login, data, params, onSuccess, onError);
		}

		function logout(data, params, onSuccess, onError) {
			apiMethods.create(Logout, data, params, onSuccess, onError);
		}

		function getProfile(params, onSuccess, onError) {
			apiMethods.get(Profile, params, onSuccess, onError);
		}

		function updateProfile(data, params, onSuccess, onError) {
			apiMethods.update(Profile, data, params, onSuccess, onError);
		}

	}

	angular
		.module('api')
		.service('personDataService', personDataService);

}());