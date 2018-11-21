(function () {
	"use strict";

	function controller(UtilService, locationDataService, $scope) {
		var vm = this;
		vm.seeMore = seeMore;
		vm.show_countries = 10;
		vm.emitClearFilters = emitClearFilters;

		init();

		function init() {
			getCountries();
		}

		function getCountries() {
			function onGetContriesSuccess(data) {
				vm.countries = angular.copy(data);
			}

			var params = {
				only_with_boxes: true
			}

			locationDataService.getCountry(params, onGetContriesSuccess, UtilService.onError);
		}

		function seeMore(value) {
			switch(value) {
				case 'countries':
					if(vm.show_countries < vm.countries.meta.total_count)
						vm.show_countries += 10;
					break;
				default:
					break;
			}
		}

        /**
         * Emit event for explore-products.js to clear filters
         */
        function emitClearFilters() {
            $scope.$emit('clearAllFilters');
        }
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/discover/boxes/filters/filters.html',
		controller: controller,
		controllerAs: 'exploreBoxesFiltersCtrl',
		bindings: {
			searching:'<',
			filters: '<'
		}
	}

	angular
		.module('discover')
		.component('exploreBoxesFilters', component);

}());