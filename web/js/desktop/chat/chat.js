(function () {
	"use strict";

	function controller(UtilService, chatDataService) {
		var vm = this;
		vm.getChats=getChats;
		vm.loading=true;

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
				vm.loading=false;
			}
			chatDataService.getChats({}, onGetChatsSuccess, UtilService.onError);
		}
	}

	angular
	.module('todevise')
	.controller('chatCtrl', controller);
}());