(function() {
    "use strict";

    function controller() {
        var vm = this;
        vm.login = currentHost() + '/login';
        vm.signup = currentHost() + '/signup';
        vm.dismiss = dismiss;

        function dismiss() {
            vm.close();
        }
    }

    var component = {
        templateUrl: currentHost() + '/js/util/modal-love-post/modal-love-post.html',
        controller: controller,
        controllerAs: 'modalLovePostCtrl',
        bindings: {
            dismiss: '&',
            close: '&',
            resolve: '<'
        }
    }

    angular
        .module('util')
        .component('modalLovePost', component);

}());