(function() {
    "use strict";

    function controller(personDataService, UtilService, lovedDataService) {
        var vm = this;
        vm.parseDate = UtilService.parseDate;
        vm.loveTimeline = loveTimeline;
        vm.unLoveTimeline = unLoveTimeline;
        vm.parseImage = parseImage;

        init();

        function init() {
            getTimeline();
        }

        function getTimeline() {
            vm.loading = true;
            function onGetTimelineSuccess(data) {
                vm.timeline = data.items;
                vm.loading = false;
            }

            function onGetTimelineError(err) {
                vm.loading = false;
                UtilService.onError(err);
            }
            personDataService.getTimeline({}, onGetTimelineSuccess, onGetTimelineError);
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