(function() {
    "use strict";

    function controller($scope, personDataService, UtilService, $window) {
        var vm = this;
        vm.addMoreItems = addMoreItems;
        vm.setFollow = setFollow;
        vm.isConnectedUser = isConnectedUser;
        var show_items = 6;
        vm.refreshResults = true;

        function addMoreItems() {
            var last = vm.results_infinite.length;
            vm.results_infinite = vm.results_infinite.concat(vm.results.items.slice(last, last + show_items));
        }

        function setFollow(item) {
            var connectedUser = UtilService.getConnectedUser();
            if (!connectedUser) {
                $window.location.href = '/timeline';
            } else {
                vm.refreshResults = false;

                function onSetFollowSuccess(data) {
                    item.is_followed = !item.is_followed;
                }

                function onSetFollowError(err) {
                    UtilService.onError(err);
                    vm.refreshResults = true;
                }
                var params = {
                    personId: item.id
                }
                if (item.is_followed) {
                    personDataService.unFollowPerson(params, params, onSetFollowSuccess, onSetFollowError);
                } else {
                    personDataService.followPerson(params, params, onSetFollowSuccess, onSetFollowError);
                }
            }
        }

		function isConnectedUser(short_id) {
			return UtilService.isConnectedUser(short_id);
		}

        $scope.$watch('discoverResultsCtrl.results', function(newValue, oldValue) {
            if (angular.isObject(newValue) && vm.refreshResults) {
                if (newValue.items.length > 0)
                    vm.results_infinite = newValue.items.slice(0, show_items);
                else {
                    vm.results_infinite = [];
                }
            }
            vm.refreshResults = true;
        }, true)
    }

    var component = {
        templateUrl: currentHost() + '/js/desktop/discover/person/results/results.html',
        controller: controller,
        controllerAs: 'discoverResultsCtrl',
        bindings: {
            results: '<'
        }
    }

    angular
        .module('discover')
        .component('discoverResults', component);

}());