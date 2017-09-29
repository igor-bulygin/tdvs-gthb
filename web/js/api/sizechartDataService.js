(function () {
	"use strict";
	
	function sizechartDataService($resource, apiConfig, apiMethods) {
		//pub
		var Sizechart = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'sizechart');
		var Countries = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'countries');
		//priv
		var DeviserSizechart = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'sizechart');
		//functions
		this.getSizechart = getSizechart;
		this.getDeviserSizechart = getDeviserSizechart;
		this.postDeviserSizechart=postDeviserSizechart;
		this.getCountries=getCountries;

		function getSizechart(params, onSuccess, onError) {
			apiMethods.get(Sizechart, params, onSuccess, onError);
		}

		function getDeviserSizechart(params, onSuccess, onError) {
			apiMethods.get(DeviserSizechart, params, onSuccess, onError);
		}

		function postDeviserSizechart(data, params, onSuccess, onError) {
			apiMethods.create(DeviserSizechart, data, params, onSuccess, onError);
		}

		function getCountries(params, onSuccess, onError) {
			apiMethods.get(Countries, params, onSuccess, onError);
		}
	}
	
	angular.module('api')
		.service('sizechartDataService', sizechartDataService);
}());