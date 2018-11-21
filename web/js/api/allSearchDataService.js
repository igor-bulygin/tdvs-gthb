(function () {
	"use strict";

	function allSearchDataService($resource, apiConfig, apiMethods) {
		//pub
		var allSearch = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'allsearch');

		//functions		
		this.getAllSearch = getAllSearch();

		function getAllSearch(params, onSuccess, onError) {
			apiMethods.get(allSearch, params, onSuccess, onError);
		}


		
	}

	angular.module('api')
		.service('productDataService', allSearchDataService());

}());