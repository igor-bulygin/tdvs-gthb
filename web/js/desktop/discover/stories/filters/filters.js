(function() {
    "use strict";

    function controller(UtilService, locationDataService, productDataService) {
        var vm = this;
        vm.seeMore = seeMore;
        vm.show_countries = 10;
        vm.show_categories = 10;


        init();

        function init() {
            getCategories();
            getCountries();
        }

        function getCountries() {
            function onGetContriesSuccess(data) {
                vm.countries = angular.copy(data);
            }

            var params = {
                only_with_stories: true
            }

            locationDataService.getCountry(params, onGetContriesSuccess, UtilService.onError);
        }

        function seeMore(value) {
            switch (value) {
                case 'countries':
                    if (vm.show_countries < vm.countries.meta.total_count)
                        vm.show_countries += 10;
                    break;
                case 'categories':
                    if (vm.show_categories < vm.categories.meta.total_count)
                        vm.show_categories += 10;
                    break;
                default:
                    break;
            }
        }

        function getCategories() {
            function onGetCategoriesSuccess(data) {
                vm.categories = angular.copy(data);
            }

            var params = {
                only_with_stories: true
            }

            productDataService.getCategories(params, onGetCategoriesSuccess, UtilService.onError);
        }

    }

    var component = {
        templateUrl: currentHost() + '/js/desktop/discover/stories/filters/filters.html',
        controller: controller,
        controllerAs: 'exploreStoriesFiltersCtrl',
        bindings: {
            filters: '<'
        }
    }

    angular
        .module('todevise')
        .component('exploreStoriesFilters', component);

}());