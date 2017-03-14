(function () {
	"use strict";


	function interceptorsRegistry($httpProvider) {
		$httpProvider.interceptors.push(securityInterceptor);
	}

	function securityInterceptor($injector, $q, $rootScope, localStorageUtilService) {
		function requestInterceptor(request) {
			if(localStorageUtilService.getLocalStorage('access_token')) {
				request.headers.Authorization = 'Bearer ' + localStorageUtilService.getLocalStorage('access_token');
			}
			return request ||$q.when(request);
		}
		return {
			request: requestInterceptor
		}
	}

	function methods() {
		this.get = get;
		this.deleteItem = deleteItem;
		this.create = create;
		this.update = update;

		function parseInfo(resource, data) {
			var o = new resource;
			return Object.assign(o, data);
		}

		function get(resource, params, onSuccess, onError) {
			resource.get(params).$promise.then(function(returnData) { onSuccess(returnData); }, function(err) { onError(err); });
		}

		function deleteItem(resource, params, onSuccess, onError) {
			resource.delete(params).$promise.then(function(returnData) { onSuccess(returnData); }, function(err) { onError(err); });
		}

		function create(resource, data, params, onSuccess, onError) {
			var newResource = parseInfo(resource, data);
			newResource.$save(params).then(function(returnData) { onSuccess(returnData); }, function(err) { onError(err); });
		}

		function update(resource, data, params, onSuccess, onError) {
			var newResource = parseInfo(resource, data);
			newResource.$update(params).then(function(returnData) { onSuccess(returnData); }, function(err) { onError(err); });
		}
	}

	angular.module('api', ['ngResource', 'util'])
		.constant('apiConfig', {
			baseUrl: currentHost() + '/api3/',
			version: 'v1/'
		})
		.config(interceptorsRegistry)
		.service('apiMethods', methods);

}());