(function () {
	"use strict";

	function controller(UtilService, chatDataService) {
		var vm = this;
		vm.getChats=getChats;
		vm.sendMsg= sendMsg;
		vm.loading=true;
		if (person) {
			vm.person = {id:person.id, name:angular.copy(person.name)};
		}
		if (person_to_chat) {
			vm.personToChat = {id:person_to_chat.id, name:angular.copy(person_to_chat.name)};
		}
		vm.chatId = chat_id;
		// vm.personToChat = {id:'3783das', personal_info:{name:"Natural Heritage"}};

		vm.tabs = [{title:"All", id : 0 }, {title:"Devisers", id : 2 }, {title:"Customers", id : 1 }, {title:"Influencers", id : 3 }];
		vm.chats=[];

		init();

		function init() {
			getChats();
		}

		function getChats() {
			vm.loading=true;
			function onGetChatsSuccess(data) {
				vm.chats = angular.copy(data.items); 
				if (vm.personToChat) {
				getChat(vm.chatId);
			}
				vm.loading=false;
			}
			chatDataService.getChats({}, onGetChatsSuccess, UtilService.onError);
		}

		function getChat(id) {
			vm.loading=true;
			function onGetChatSuccess(data) {
				vm.currentChat = angular.copy(data); 
				vm.loading=false;
			}
			if (id) {
				chatDataService.getChat({id:id}, onGetChatSuccess, UtilService.onError);
			}
			else {
				createChat();
			}
		}

		function createChat() {
			vm.currentChat =  {"preview": {"title": vm.personToChat.name}, messages:[]};
			vm.chats.unshift(vm.currentChat);
		}

		function sendMsg() {
			if (vm.newMsg && vm.newMsg.length>0) {
				function onSendMsgSuccess(data) {
					vm.currentChat.messages.push(data.messages[0]); 
					vm.newMsg = '';
					getChats(); // update chats after sending new message
				}
				chatDataService.sendMsg({text:vm.newMsg },{personId: vm.personToChat.id}, onSendMsgSuccess, UtilService.onError);
			}
		}
	}

	angular
	.module('todevise')
	.controller('chatCtrl', controller);
}());