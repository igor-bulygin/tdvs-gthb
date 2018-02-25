(function () {
	"use strict";

	function chatDataService($resource, apiConfig, apiMethods) {
		//priv
		var Chats = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'chats/:personType');
		var Chat = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'chat/:id');
		var Msg = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'chat/send-message/:personId');

		//functions
		this.getChats = getChats;
		this.getChat = getChat;
		this.sendMsg = sendMsg;

		function getChats(params, onSuccess, onError) {
			apiMethods.get(Chats, params, onSuccess, onError);
		}

		function getChat(params, onSuccess, onError) {
			apiMethods.get(Chat, params, onSuccess, onError);
		}

		function sendMsg(data, params, onSuccess, onError) {
			apiMethods.create(Msg, data, params, onSuccess, onError);
		}
	}

	angular
		.module('api')
		.service('chatDataService', chatDataService);
}());