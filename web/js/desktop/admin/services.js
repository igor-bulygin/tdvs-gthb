var global = angular.module('global');

global.service("$tag", function($http, $q) {

	var api_point = currentHost() + "/api/tags/";

	return ({

		_handleSuccess: function(response) {
			return response.data;
		},

		_handleError: function(response) {
			if (!angular.isObject(response.data) || !response.data.message) {
				return $q.reject("An unknown error occurred.");
			}
			return $q.reject(response.data.message);
		},

		get: function(filters) {
			var req = $http({
				method: "get",
				headers: {
					'X-Requested-With': 'XMLHttpRequest'
				},
				url: api_point + "?filters=" + objectToQueryParam(filters)
			});

			return req.then(this._handleSuccess, this._handleError);
		},

		modify: function(method, node) {
			var csrf = yii.getCsrfToken();
			var req  = $http({
				method: method,
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'X-CSRF-Token': csrf
				},
				url: api_point,
				data: {
					category: node,
					_csrf: csrf
				}
			});

			return req.then(this._handleSuccess, this._handleError);
		}
	});
});

global.service("$category", function($http, $q) {

	var api_point = currentHost() + "/api/categories/";

	return ({

		_handleSuccess: function(response) {
			return response.data;
		},

		_handleError: function(response) {
			if (!angular.isObject(response.data) || !response.data.message) {
				return $q.reject("An unknown error occurred.");
			}
			return $q.reject(response.data.message);
		},

		get: function() {
			var req = $http({
				method: "get",
				headers: {
					'X-Requested-With': 'XMLHttpRequest'
				},
				url: api_point
			});

			return req.then(this._handleSuccess, this._handleError);
		},

		modify: function(method, node) {
			var csrf = yii.getCsrfToken();
			var req  = $http({
				method: method,
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'X-CSRF-Token': csrf
				},
				url: api_point,
				data: {
					category: node,
					_csrf: csrf
				}
			});

			return req.then(this._handleSuccess, this._handleError);
		}
	});
});