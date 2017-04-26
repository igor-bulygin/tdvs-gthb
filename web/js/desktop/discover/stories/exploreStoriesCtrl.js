(function() {
    "use strict";

    function controller(UtilService, storyDataService, $scope) {
        var vm = this;
        vm.search = search;
        vm.filters = {};
        init();

        function init() {
            search();
        }

        function search(form) {
            delete vm.results;
            vm.searching = true;
            var params = {
                ignore_empty_stories: true
            }
            if (vm.key)
                params = Object.assign(params, { q: vm.key });

            Object.keys(vm.filters).map(function(filter_type) {
                var new_filter = []
                Object.keys(vm.filters[filter_type]).map(function(filter) {
                    if (vm.filters[filter_type][filter])
                        new_filter.push(filter);
                })
                if (new_filter.length > 0)
                    params[filter_type + '[]'] = new_filter;
            })

            function onGetStoriesSuccess(data) {
                vm.searching = false;
                vm.search_key = angular.copy(vm.key);
                vm.results = angular.copy(data);
                if (vm.results.items.length > 0) {}
            }

            function onGetStoriesError(err) {
                UtilService.onError(err);
                vm.searching = false;
            }
            storyDataService.getStoryPub(params, onGetStoriesSuccess, onGetStoriesError);
        }

        //watches
        $scope.$watch('exploreStoriesCtrl.filters', function(newValue, oldValue) {
            search(vm.form)
        }, true);
    }

    angular
        .module('todevise')
        .controller('exploreStoriesCtrl', controller);

}())