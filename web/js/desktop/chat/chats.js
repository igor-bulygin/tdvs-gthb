(function() {
    "use strict";

    function controller(UtilService, chatDataService, $window, $location, $scope, $anchorScroll) {
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
        vm.unselectChat = unselectChat;
        if (person) {
            vm.person = { id: person.id, name: angular.copy(person.name), profile_image: person.profile_image };
        }
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
                if (vm.personToChat && (vm.firstCharge || !filtering) && !vm.currentChat) {
                    getChat(vm.chatId);
                } else if (!vm.firstCharge && filtering && vm.chats && vm.chats.length > 0) {
                    if ((vm.currentChat && vm.currentChat.id != vm.chats[0].id) || !vm.currentChat) {
                        // var newUrl = vm.chats[0].preview.url + '?filterId=' + vm.filterId;
                        // $window.open(newUrl, "_self");
                    }
                } else if (filtering) {
                    vm.currentChat = null;
                }
                vm.loading = false;
                vm.firstCharge = false;
            }
            chatDataService.getChats({ person_type: vm.filterId }, onGetChatsSuccess, UtilService.onError);
        }

        function getChat(id) {
            vm.loadingChat = true;

            function onGetChatSuccess(data) {
                vm.currentChat = angular.copy(data);
                var lastOwner;
                angular.forEach(vm.currentChat.messages, function(msg) {
                    if (lastOwner && lastOwner === msg.person_id) {
                        msg.showOwner = false;
                    } else {
                        lastOwner = msg.person_id;
                        msg.showOwner = true;
                    }
                });
                vm.loadingChat = false;
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
                    var addedMsg = data.messages[data.messages.length - 1];
                    addedMsg.person_info = { name: vm.person.name, profile_image: vm.person.profile_image };
                    vm.currentChat.messages.push(addedMsg);
                    vm.newMsg = '';
                    $location.hash('bottomChat');
                    $anchorScroll();
                    getChats(); // update chats after sending new message
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
            var baseLen = $location.absUrl().length - $location.url().length;
            $location.path(chat.preview.url.substring(baseLen));
            getChat(chat.id)
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
                return 'msgOwner col-xs-7 col-xs-offset-4 col-sm-6 col-sm-offset-5';
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