(function () {
	"use strict";
	
	function metricDataService($resource, apiConfig) {
		//pub
		this.Metric = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'metric');
	}
	
	angular.module('api')
		.service('metricDataService', metricDataService);
}());