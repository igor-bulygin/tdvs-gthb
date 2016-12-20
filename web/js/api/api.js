(function () {
	"use strict";

	angular.module('api', ['ngResource', 'util'])
		.constant('apiConfig', {
			baseUrl: currentHost() + '/api3/',
			version: 'v1/'
		})
		.config(interceptorsRegistry);

	function interceptorsRegistry($httpProvider) {
		$httpProvider.interceptors.push(securityInterceptor);
	}

	function securityInterceptor($injector, $q, $rootScope, UtilService) {
		function requestInterceptor(request) {
			if(UtilService.getLocalStorage('access_token')) {
				request.headers.Authorization = 'Bearer ' + UtilService.getLocalStorage('access_token');
			}
			return request ||$q.when(request);
		}
		return {
			request: requestInterceptor
		}
	}

}());