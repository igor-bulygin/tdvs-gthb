(function () {
	"use strict";

	function personDataService($resource, apiConfig, apiMethods, Upload) {
		//pub
		var Person = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'person/:personId');
		var Login = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'auth/login');
		var Logout = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'auth/logout');

		//priv
		var Profile = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'person/:personId', {}, {
			'update': {
				method: 'PATCH'
			}
		});
		var Uploads = apiConfig.baseUrl + 'priv/' + apiConfig.version + 'uploads';

		//methods
		this.getPeople = getPeople;
		this.createClient = createClient;
		this.createDeviser = createDeviser;
		this.createInfluencer = createInfluencer;
		this.login = login;
		this.logout = logout;
		this.getProfile = getProfile;
		this.getProfilePublic = getProfilePublic;
		this.updateProfile = updateProfile;
		this.UploadFile = UploadFile;

		function getPeople(params, onSuccess, onError) {
			apiMethods.get(Person, params, onSuccess, onError);
		}

		function createClient(data, params, onSuccess, onError) {
			data = Object.assign(data, {type: [1]});
			apiMethods.create(Person, data, params, onSuccess, onError);
		}

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

		function getProfilePublic(params, onSuccess, onError) {
			apiMethods.get(Person, params, onSuccess, onError);
		}

		function getProfile(params, onSuccess, onError) {
			apiMethods.get(Profile, params, onSuccess, onError);
		}

		function updateProfile(data, params, onSuccess, onError) {
			apiMethods.update(Profile, data, params, onSuccess, onError);
		}

		function UploadFile(data, onSuccess, onError, onUploading) {
			Upload.upload({
				url: Uploads,
				data: data
			}).then(function(returnData) { onSuccess(returnData)}, function(err) { onError(err); }, function(evt) { onUploading(evt)} );
		}

	}

	angular
		.module('api')
		.service('personDataService', personDataService);

}());