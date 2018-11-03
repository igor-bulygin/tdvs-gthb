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
		};
	}

	function methods() {
		this.get = get;
		this.query=query;
		this.deleteItem = deleteItem;
		this.create = create;
		this.update = update;
		this.getUnresolved = getUnresolved;

		function parseInfo(resource, data) {
			var o = new resource;
			return Object.assign(o, data);
		}

		function get(resource, params, onSuccess, onError) {
			resource.get(params).$promise.then(function(returnData) { onSuccess(returnData); }, function(err) { onError(err); });
		}

		function query(resource, params, onSuccess, onError) {
			resource.query(params).$promise.then(function(returnData) { onSuccess(returnData); }, function(err) { onError(err); });
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

        /**
         * @param resource
         * @param params - GET params
         * @return Promise for use in Promise.all
         */
		function getUnresolved(resource, params) {
            return resource.get(params).$promise;
        }

	}

	angular.module('api', ['ngResource', 'util', 'ngFileUpload'])
		.constant('apiConfig', {
			baseUrl: currentHost() + '/api3/',
			version: 'v1/'
		})
		.config(interceptorsRegistry)
		.service('apiMethods', methods);

}());