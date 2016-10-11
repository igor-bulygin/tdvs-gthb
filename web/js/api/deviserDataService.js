(function () {
	"use strict";

	function deviserDataService($resource, apiConfig) {
		//pub
		this.InvitationRequest = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'devisers/invitation-requests');
		this.Invitation = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'invitations/:idInvitation');
		this.Devisers = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'devisers');

		this.Profile = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'profile/deviser', {}, {
			'update': {
				method: 'PATCH'
			},
			'put': {
				method: 'PUT'
			}
		});
		this.Uploads = apiConfig.baseUrl + "priv/" + apiConfig.version + 'uploads';
	}

	angular.module('api')
		.service('deviserDataService', deviserDataService);
}());