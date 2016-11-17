(function () {
	"use strict";
	
	function sizechartDataService($resource, apiConfig) {
		//pub
		this.Sizechart = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'sizechart');
	}
	
	angular.module('api')
		.service('sizechartDataService', sizechartDataService);
}());