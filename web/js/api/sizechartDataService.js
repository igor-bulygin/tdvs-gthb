(function () {
	"use strict";
	
	function sizechartDataService($resource, apiConfig, apiMethods) {
		//pub
		var Sizechart = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'sizechart');

		//functions
		this.getSizechart = getSizechart;

		function getSizechart(params, onSuccess, onError) {
			apiMethods.get(Sizechart, params, onSuccess, onError);
		}
	}
	
	angular.module('api')
		.service('sizechartDataService', sizechartDataService);
}());