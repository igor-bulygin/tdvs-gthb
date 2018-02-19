(function () {
	"use strict";

	function controller(UtilService, chatDataService, $window) {
		var vm = this;
		vm.getChats=getChats;
		vm.sendMsg= sendMsg;
		vm.changeChatFilter = changeChatFilter;
		vm.parseDate=UtilService.parseDate;
		vm.loading=true;
		if (person) {
			vm.person = {id:person.id, personal_info:angular.copy(person.personal_info)};
		}
		if (person_to_chat) {
			vm.personToChat = {id:person_to_chat.id, personal_info:angular.copy(person_to_chat.personal_info)};
		}
		vm.chatId = chat_id;
		vm.personToChat = {id:'3783das', personal_info:{name:"Natural Heritage"}};

		vm.tabs = [{title:"All", id : 0 }, {title:"Devisers", id : 2 }, {title:"Customers", id : 1 }, {title:"Influencers", id : 3 }];
		vm.chats=[];

		init();

		function init() {
			getChats();
		}

		function getChats(filtering) {
			vm.loading=true;
			function onGetChatsSuccess(data) {
				vm.chats = angular.copy(data.items); 
				if (vm.personToChat && !filtering && !vm.currentChat) {
					getChat(vm.chatId);
				}
				else if (filtering && vm.chats && vm.chats.length>0) {
					if ((vm.currentChat && vm.currentChat.id != vm.chats[0].id) || !vm.currentChat ) {
						$window.open(vm.chats[0].preview.url, "_self");
					}
				}
				else if (filtering) {
					vm.currentChat = null;
				}
				vm.loading=false;
			}
			chatDataService.getChats({person_type: vm.filterId}, onGetChatsSuccess, UtilService.onError);
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
			vm.currentChat =  {"preview": {"title": vm.personToChat.personal_info.name}, messages:[]};
			vm.chats.push(vm.currentChat);
		}

		function sendMsg() {
			if (vm.newMsg && vm.newMsg.length>0) {
				function onSendMsgSuccess(data) {
					vm.currentChat.messages.push(data.messages[0]); 
					vm.newMsg = '';
				}
				chatDataService.sendMsg({text:vm.newMsg },{personId: vm.personToChat.id}, onSendMsgSuccess, UtilService.onError);
			}
		}

		function changeChatFilter(filterId) {
			vm.filterId=filterId;
			getChats(true);
		}
	}

	angular
	.module('todevise')
	.controller('chatCtrl', controller);
}());