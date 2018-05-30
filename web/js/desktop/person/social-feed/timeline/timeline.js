(function() {
    "use strict";

    function controller(personDataService, UtilService) {
        var vm = this;
        vm.parseDate = UtilService.parseDate;

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
    }

    angular
        .module('person')
        .controller('timelineCtrl', controller);

}());