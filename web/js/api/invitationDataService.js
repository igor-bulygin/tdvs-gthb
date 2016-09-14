(function () {
	"use strict";

	function invitationDataService($resource, config) {
		//pub
		this.Invitation = $resource(config.baseUrl + 'admin/' + config.version + 'invitations');
	}

	angular.module('api')
		.service('invitationDataService', invitationDataService);
}());