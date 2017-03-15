(function () {
	"use strict";

	function invitationDataService($resource, apiConfig, apiMethods) {
		//admin
		var InvitationAdmin = $resource(apiConfig.baseUrl + 'admin/' + apiConfig.version + 'invitations');
		//pub
		var Invitation = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'invitations/:idInvitation');
		var InvitationRequest = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'invitation/request-become-deviser');

		//methods
		this.getInvitation = getInvitation;
		this.createInvitationRequest = createInvitationRequest;
		this.createInvitationAdmin = createInvitationAdmin;

		function getInvitation(params, onSuccess, onError) {
			apiMethods.get(Invitation, params, onSuccess, onError);
		}

		function createInvitationRequest(data, params, onSuccess, onError) {
			apiMethods.create(InvitationRequest, data, params, onSuccess, onError);
		}

		function createInvitationAdmin(data, params, onSuccess, onError) {
			apiMethods.create(InvitationAdmin, data, params, onSuccess, onError);
		}

	}

	angular
		.module('api')
		.service('invitationDataService', invitationDataService);
}());