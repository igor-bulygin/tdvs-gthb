(function () {
	"use strict";

	function invitationDataService($resource, apiConfig, apiMethods) {
		//admin
		var InvitationAdmin = $resource(apiConfig.baseUrl + 'admin/' + apiConfig.version + 'invitations');
		//pub
		var Invitation = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'invitations/:idInvitation');
		var InvitationRequestDeviser = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'invitation/request-become-deviser');
		var InvitationRequestInfluencer = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'invitation/request-become-influencer');

		//methods
		this.getInvitation = getInvitation;
		this.createInvitationRequestDeviser = createInvitationRequestDeviser;
		this.createInvitationRequestInfluencer = createInvitationRequestInfluencer;
		this.createInvitationAdmin = createInvitationAdmin;

		function getInvitation(params, onSuccess, onError) {
			apiMethods.get(Invitation, params, onSuccess, onError);
		}

		function createInvitationRequestDeviser(data, params, onSuccess, onError) {
			apiMethods.create(InvitationRequestDeviser, data, params, onSuccess, onError);
		}

		function createInvitationRequestInfluencer(data, params, onSuccess, onError) {
			apiMethods.create(InvitationRequestInfluencer, data, params, onSuccess, onError);
		}

		function createInvitationAdmin(data, params, onSuccess, onError) {
			apiMethods.create(InvitationAdmin, data, params, onSuccess, onError);
		}

	}

	angular
		.module('api')
		.service('invitationDataService', invitationDataService);
}());