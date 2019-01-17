(function() {
    "use strict";

    function controller(UtilService, lovedDataService) {
        var vm = this;
        vm.ok = ok;
        vm.dismiss = dismiss;
        vm.lovePost = lovePost;
        vm.unLovePost = unLovePost;

        init();

        function init() {}

        function ok() {
            vm.close({
                $value: vm.resolve.link
            })
        }

        function dismiss() {
            vm.close();
        }

        function lovePost(post) {
          console.log(post);
          console.log("hola");
            var connectedUser = UtilService.getConnectedUser();
            if (!connectedUser) {
                modalLogin($uibModal, "person.SOCIAL");
            }
            if (post.person_id === connectedUser || post.isLoved) {
                return;
            }
            vm.loading = true;

            function onLovePostSuccess(data) {
                post.loveds = data.post.loveds;
                post.isLoved = data.post.isLoved;
                vm.loading = false;
            }

            function onLovePostError(err) {
                vm.loading = false;
                UtilService.onError(err);
            }
            lovedDataService.setLoved({ post_id: post.id }, onLovePostSuccess, onLovePostError);
        }

        function unLovePost(post) {
            var connectedUser = UtilService.getConnectedUser();
            if (!connectedUser) {
                modalLogin();
            }
            if (post.person_id === connectedUser || !post.isLoved) {
                return;
            }
            vm.loading = true;

            function onUnLovePostSuccess(data) {
                post.loveds = post.loveds - 1;
                post.isLoved = false;
                vm.loading = false;
            }

            function onUnLovePostError(err) {
                vm.loading = false;
                UtilService.onError(err);
            }
            lovedDataService.deleteLovedPost({ postId: post.id }, onUnLovePostSuccess, onUnLovePostError);
        }
    }

    var component = {
        templateUrl: currentHost() + '/js/desktop/person/social-feed/modal-post-details/modal-post-details.html',
        controller: controller,
        controllerAs: 'modalPostDetailsCtrl',
        bindings: {
            resolve: '<',
            close: '&',
            dismiss: '&'
        }
    }

    angular
        .module('person')
        .component('modalPostDetails', component);

}());
