(function () {
	"use strict";
	
	function sizechartDataService($resource, apiConfig, apiMethods) {
		//pub
		var Sizechart = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'sizechart');
		//priv
		var DeviserSizechart = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'sizechart');
		//functions
		this.getSizechart = getSizechart;
		this.postDeviserSizechart=postDeviserSizechart;

		function getSizechart(params, onSuccess, onError) {
			apiMethods.get(Sizechart, params, onSuccess, onError);
		}

		function postDeviserSizechart(data, params, onSuccess, onError) {
			apiMethods.create(DeviserSizechart, data, params, onSuccess, onError);
		}
	}
	
	angular.module('api')
		.service('sizechartDataService', sizechartDataService);
}());