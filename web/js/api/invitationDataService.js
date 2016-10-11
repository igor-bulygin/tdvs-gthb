(function () {
	"use strict";

	function invitationDataService($resource, apiConfig) {
		//pub
		this.Invitation = $resource(apiConfig.baseUrl + 'admin/' + apiConfig.version + 'invitations');
	}

	angular.module('api')
		.service('invitationDataService', invitationDataService);
}());