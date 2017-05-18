(function () {
	"use strict";
	
	function metricDataService($resource, apiConfig, apiMethods) {
		//pub
		var Metric = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'metric');
		var Currency = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'currency');

		//functions
		this.getMetric = getMetric;
		this.getCurrencies = getCurrencies;

		function getMetric(onSuccess, onError) {
			apiMethods.get(Metric, null, onSuccess, onError);
		}

		function getCurrencies(params, onSuccess, onError) {
			apiMethods.query(Currency,params, onSuccess, onError);
		}

	}
	
	angular.module('api')
		.service('metricDataService', metricDataService);
}());