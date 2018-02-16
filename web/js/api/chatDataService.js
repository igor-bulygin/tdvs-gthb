(function () {
	"use strict";

	function chatDataService($resource, apiConfig, apiMethods) {
		//priv
		var chats = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'chats/:personType');

		//functions
		this.getChats = getChats;

		function getChats(params, onSuccess, onError) {
			apiMethods.get(chats, params, onSuccess, onError);
		}
	}

	angular
		.module('api')
		.service('chatDataService', chatDataService);
}());