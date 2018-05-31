(function() {
    "use strict";

    function controller(personDataService, UtilService, lovedDataService) {
        var vm = this;
        vm.parseDate = UtilService.parseDate;
        vm.loveTimeline = loveTimeline;
        vm.unLoveTimeline = unLoveTimeline;
        vm.parseImage = parseImage;
        vm.show_items = 21;
        vm.addMoreItems = addMoreItems;
        vm.results_infinite = [];
        vm.searchPage=1;
        vm.timeline = [];
        vm.connectedUser = connectedUser;

        init();

        function init() {
            getTimeline();
        }

        function getTimeline(page) {
            vm.loading = true;
            function onGetTimelineSuccess(data) {
                vm.timeline = vm.timeline.concat(data.items);
                vm.loading = false;
            }

            function onGetTimelineError(err) {
                vm.loading = false;
                UtilService.onError(err);
            }
            personDataService.getTimeline({page: page, limit: vm.show_items }, onGetTimelineSuccess, onGetTimelineError);
        }

        function connectedUser() {
            return !angular.isUndefined(UtilService.getConnectedUser());
        }

        function addMoreItems() {
            vm.searchPage = vm.searchPage + 1;
            getTimeline(vm.searchPage);
        }

        function loveTimeline(timeline) {
            if (timeline.person_id === UtilService.getConnectedUser() || timeline.isLoved) {
                return;
            }
            vm.loading = true;

            function onLoveTimelineSuccess(data) {
                timeline.loveds = data.timeline.loveds;
                timeline.isLoved = data.timeline.isLoved;
                vm.loading = false;
            }

            function onLoveTimelineError(err) {
                vm.loading = false;
                UtilService.onError(err);
            }
            lovedDataService.setLoved({ timeline_id: timeline.id }, onLoveTimelineSuccess, onLoveTimelineError);
        }

        function unLoveTimeline(timeline) {
            if (timeline.person_id === UtilService.getConnectedUser() || !timeline.isLoved) {
                return;
            }
            vm.loading = true;

            function onUnLoveTimelineSuccess(data) {
                timeline.loveds = timeline.loveds - 1;
                timeline.isLoved = false;
                vm.loading = false;
            }

            function onUnLoveTimelineError(err) {
                vm.loading = false;
                UtilService.onError(err);
            }
            lovedDataService.deleteLovedTimeline({ timelineId: timeline.id }, onUnLoveTimelineSuccess, onUnLoveTimelineError);
        }

        function parseImage(image) {
            var res= image;
            if (image.indexOf('http') == -1) {
                res = currentHost() + image;
            }
            return res;
        }
    }

    angular
        .module('person')
        .controller('timelineCtrl', controller);

}());