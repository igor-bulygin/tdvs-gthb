(function () {
	"use strict";
	
	function sizechartDataService($resource, apiConfig) {
		//pub
		var Sizechart = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'sizechart');

		//functions
		this.getSizechart = getSizechart;

		function getSizechart(params, onsuccess, onerror) {
			Sizechart.get(params)
				.$promise.then(function (returnData) {
					onsuccess(returnData)
				},function(err) {
					onerror(err);
				});
		}
	}
	
	angular.module('api')
		.service('sizechartDataService', sizechartDataService);
}());