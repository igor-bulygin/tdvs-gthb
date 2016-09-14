(function () {
	"use strict";

	function deviserDataService($resource, config) {
		//pub
		this.InvitationRequest = $resource(config.baseUrl + 'pub/' + config.version + 'devisers/invitation-requests');
		this.Invitation = $resource(config.baseUrl + 'pub/' + config.version + 'invitations/:idInvitation');
		this.Devisers = $resource(config.baseUrl + 'pub/' + config.version + 'devisers');

		this.Profile = $resource(config.baseUrl + 'priv/' + config.version + 'profile/deviser', {}, {
			'update': {
				method: 'PATCH'
			}
		});
		this.Uploads = config.baseUrl + "priv/" + config.version + 'uploads';
	}

	angular.module('api')
		.service('deviserDataService', deviserDataService);
}());