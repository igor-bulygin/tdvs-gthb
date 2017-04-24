(function() {
    'use strict';

    function controller(UtilService, storyDataService, $scope) {
        var vm = this;

        init();

        function init() {
            GetStories();
        }

        function GetStories() {
            function onGetStoriesSuccess(data) {
                vm.results = angular.copy(data);
            }

            function onGetStoriesError(err) {
                UtilService.onError(err);
            }
            storyDataService.getStoryPriv({}, onGetStoriesSuccess, onGetStoriesError);
        }
    }

    angular
        .module('todevise')
        .controller('viewStoriesCtrl', controller);


})();