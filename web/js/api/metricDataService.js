(function () {
	"use strict";
	
	function metricDataService($resource, apiConfig) {
		//pub
		var Metric = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'metric');

		//functions
		this.getMetric = getMetric;

		function getMetric(onsuccess, onerror) {
			Metric.get()
				.$promise.then(function(returnData) {
					onsuccess(returnData);
				}, function(err) {
					onerror(err);
				});
		}

	}
	
	angular.module('api')
		.service('metricDataService', metricDataService);
}());