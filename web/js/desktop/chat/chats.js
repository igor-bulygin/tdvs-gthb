(function() {
    "use strict";

    function controller(UtilService, chatDataService, $window, $location, $scope, $anchorScroll, $interval) {
        var vm = this;
        vm.getChats = getChats;
        vm.sendMsg = sendMsg;
        vm.changeChatFilter = changeChatFilter;
        vm.selectChat = selectChat;
        vm.parseDate = UtilService.parseDate;
        vm.parseImage = parseImage;
        vm.activeChat = activeChat;
        vm.msgOwner = msgOwner;
        vm.loading = true;
        vm.loadingChat = true;
        vm.unselectChat = unselectChat;
        vm.timerChats;
        if (person) {
            vm.person = { id: UtilService.getConnectedUser(), name: angular.copy(person.name), profile_image: person.profile_image };
        } else {
            vm.person = { id: UtilService.getConnectedUser() };
        }
        vm.personToChat = {};
        if (person_to_chat) {
            vm.personToChat = { id: person_to_chat.id, name: angular.copy(person_to_chat.name), profile_image: angular.copy(person_to_chat.profile_image) };
        }
        vm.chatId = chat_id;

        vm.tabs = [{ title: "All", id: '0' }, { title: "Devisers", id: '2' }, { title: "Customers", id: '1' }, { title: "Influencers", id: '3' }];
        vm.active = 0;
        vm.chats = [];

        init();

        function init() {
            vm.loading = true;
            vm.firstCharge = true;
            var filtering = false;
            var paramName = 'filterId=';
            var indexParam = $location.absUrl().search(paramName);
            if (indexParam != -1) {
                indexParam = indexParam + paramName.length;
                vm.filterId = $location.absUrl().slice(indexParam, $location.absUrl().length);
                if (vm.filterId && vm.filterId != 0) {
                    filtering = true;
                    angular.forEach(vm.tabs, function(value, index) {
                        if (value.id == vm.filterId) {
                            vm.active = index;
                        }
                    });

                }
            }
            getChats(filtering);
        }

        function getChats(filtering) {
            function onGetChatsSuccess(data) {
                vm.chats = angular.copy(data.items);
                if ((vm.personToChat && vm.personToChat.id) && (vm.firstCharge || !filtering) && !vm.currentChat) {
                    getChat(vm.chatId);
                } else if (filtering) {
                    vm.currentChat = null;
                }
                vm.loading = false;
                vm.loadingChat = false;
                vm.firstCharge = false;
                $location.hash('bottomChat');
                $anchorScroll();
            }
            chatDataService.getChats({ person_type: vm.filterId }, onGetChatsSuccess, UtilService.onError);
        }

        function getChat(id) {
            vm.loadingChat = true;

            function onGetChatSuccess(data) {
                vm.currentChat = angular.copy(data);
                var lastOwner;
                if (vm.currentChat.messages.length > 0) {
                    vm.currentChat.messages[vm.currentChat.messages.length - 1].isLast = true;
                }
                angular.forEach(vm.currentChat.messages, function(msg) {
                    if (lastOwner && lastOwner === msg.person_id) {
                        msg.showOwner = false;
                    } else {
                        lastOwner = msg.person_id;
                        msg.showOwner = true;
                    }
                });
                angular.forEach(vm.currentChat.members, function(member) {
                    if (vm.person.id != '"'+member.person_id+'"') {
                        vm.personToChat.id = member.person_id;
                        vm.personToChat.name = member.person_info.name;
                        vm.personToChat.profile_image = member.person_info.profile_image;
                    }
                });
                vm.loadingChat = false;
                $location.hash('bottomChat');
                $anchorScroll();
                
            }
            if (id) {
                chatDataService.getChat({ id: id }, onGetChatSuccess, UtilService.onError);
            } else {
                createChat();
            }
        }

        function createChat() {
            vm.currentChat = { "preview": { "title": vm.personToChat.name, "image": vm.personToChat.profile_image }, messages: [] };
            vm.chats.unshift(vm.currentChat);
            vm.loadingChat = false;
        }

        function sendMsg() {
            if (vm.newMsg && vm.newMsg.length > 0) {
                function onSendMsgSuccess(data) {
                    var lastOwner;
                    var OldPersonalInfo = vm.currentChat.messages;
                    var firstPersonInfo;
                    var secondPersonInfo;

                    vm.currentChat.messages = data.messages;
                    vm.currentChat.messages[vm.currentChat.messages.length - 1].isLast = true;
                    if (OldPersonalInfo[0]){
                        firstPersonInfo = OldPersonalInfo[0].person_info;
                        for(var j = 0; j < data.messages.length; j++){
                            if (data.messages[j].person_id != firstPersonInfo.id){
                                for (var u = 0; u < data.members.length; u++){
                                    if(data.messages[j].person_id == data.members[u].person_id){
                                        vm.currentChat.messages[j].person_info = data.members[u].person_info
                                        secondPersonInfo = data.members[u].person_info;
                                    }
                                }
                            }
                        }
                    }
                    else{
                        for (var i = 0; i < data.members.length; i++){
                            if (data.messages[0].person_id == data.members[i].person_id){
                                firstPersonInfo = data.members[i].person_info;
                            }
                        }
                    }
                    for(var i = 0; i < OldPersonalInfo.length; i++){
                        vm.currentChat.messages[i].person_info = OldPersonalInfo[i].person_info;
                        if (OldPersonalInfo[i].person_id != firstPersonInfo.id){
                            for (var u = 0; u < data.members.length; u++){
                                if(OldPersonalInfo[i].person_id == data.members[u].person_id){
                                    secondPersonInfo = data.members[u].person_info;
                                }
                            }
                        }
                    }
                    angular.forEach(vm.currentChat.messages, function(msg) {
                        if (lastOwner && lastOwner === msg.person_id) {
                            msg.showOwner = false;
                        } else {
                            lastOwner = msg.person_id;
                            msg.showOwner = true;
                        }
                        if (firstPersonInfo && msg.person_id == firstPersonInfo.id){
                            msg.person_info = firstPersonInfo;
                        }
                        else if(secondPersonInfo && msg.person_id == secondPersonInfo.id){
                            msg.person_info = secondPersonInfo;
                        }
                    });
                    vm.newMsg = '';
                    getChats();
                    $location.hash('bottomChat');
                    $anchorScroll();
                }
                chatDataService.sendMsg({ text: vm.newMsg }, { personId: vm.personToChat.id }, onSendMsgSuccess, UtilService.onError);
            }
        }

        function changeChatFilter(filterId) {
            vm.filterId = filterId;
            vm.loading = true;
            $location.path('/messages');
            getChats(true);
        }

        function selectChat(chat) {
            if (vm.timerChats){
                $interval.cancel(vm.timerChats);
            }
            var baseLen = $location.absUrl().length - $location.url().length;
            $location.url(chat.preview.url.substring(baseLen));
            getChat(chat.id);
             vm.timerChats = $interval(function() {
                function onGetChatSuccess(data) {
                    vm.currentChat = angular.copy(data);
                    var lastOwner;
                    if (vm.currentChat.messages.length > 0) {
                        vm.currentChat.messages[vm.currentChat.messages.length - 1].isLast = true;
                    }
                    angular.forEach(vm.currentChat.messages, function(msg) {
                        if (lastOwner && lastOwner === msg.person_id) {
                            msg.showOwner = false;
                        } else {
                            lastOwner = msg.person_id;
                            msg.showOwner = true;
                        }
                    });
                    angular.forEach(vm.currentChat.members, function(member) {
                        if (vm.person.id != '"'+member.person_id+'"') {
                            vm.personToChat.id = member.person_id;
                            vm.personToChat.name = member.person_info.name;
                            vm.personToChat.profile_image = member.person_info.profile_image;
                        }
                    });
                    getChats();
                    $location.hash('bottomChat');
                    $anchorScroll();
                }
                if (chat.id) {
                    chatDataService.getChat({ id: chat.id }, onGetChatSuccess, UtilService.onError)
                } else {
                    createChat();
                }
            }, 60000);
            function onSetChatReadSuccess(data){
                angular.forEach(vm.chats, function(chat){
                    if (chat.id == data.id){
                        chat.preview.unread = false;
                    }
                });
            }
            chatDataService.setChatRead({ chatId: chat.id }, { chatId: chat.id }, onSetChatReadSuccess, UtilService.onError)
        }

        function parseImage(image) {
            var res = image;
            if (image.indexOf('http') == -1) {
                res = currentHost() + image;
            }
            return res;
        }

        function activeChat(chat) {
            if (vm.currentChat && chat.id === vm.currentChat.id) {
                return 'activeChat';
            }
            return '';
        }

        function msgOwner(msg) {
            if (msg.person_id === vm.person.id) {
                return 'msgOwner col-xs-7 col-xs-offset-4 col-sm-6 col-sm-offset-5 ';
            }
            return '';
        }

        function unselectChat() {
            vm.currentChat = null;
        }
    }

    angular
        .module('chat')
        .controller('chatCtrl', controller);
}());
