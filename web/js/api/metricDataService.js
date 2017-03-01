(function () {
	"use strict";
	
	function metricDataService($resource, apiConfig, apiMethods) {
		//pub
		var Metric = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'metric');

		//functions
		this.getMetric = getMetric;

		function getMetric(onSuccess, onError) {
			apiMethods.get(Metric, null, onSuccess, onError);
		}

	}
	
	angular.module('api')
		.service('metricDataService', metricDataService);
}());