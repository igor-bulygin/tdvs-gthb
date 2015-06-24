var api = angular.module('api');

api.factory("$services_util", function($http, $q) {
	var api_helpers = {};

	api_helpers._handleSuccess = function(response) {
		return response.data;
	};

	api_helpers._handleError = function(response) {
		if (!angular.isObject(response.data) || !response.data.message) {
			return $q.reject("An unknown error occurred.");
		}
		return $q.reject(response.data.message);
	};

	api_helpers._get = function(url, filters) {
		filters = filters !== undefined ? "?filters=" + aus._objToStr(filters) : "";

		return $http({
			method: "get",
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			},
			url: url + filters
		});
	};

	api_helpers._modify = function(url, method, data) {
		var csrf = yii.getCsrfToken();
		data["_csrf"] = csrf;
		return $http({
			method: method,
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'X-CSRF-Token': csrf
			},
			url: url,
			data: data
		});
	};

	return api_helpers;
});

api.service("$tag", function($services_util) {

	var api_point = currentHost() + "/api/tags/";

	return ({

		get: function(filters) {
			var req = $services_util._get(api_point, filters);

			return req.then($services_util._handleSuccess, $services_util._handleError);
		},

		modify: function(method, tag) {
			var req = $services_util._modify(api_point, method, {
				tag: tag
			});

			return req.then($services_util._handleSuccess, $services_util._handleError);
		},

		delete: function(tag) {
			return this.modify("DELETE", tag);
		}
	});
});

api.service("$sizechart", function($services_util) {

	var api_point = currentHost() + "/api/size-charts/";

	return ({

		get: function(filters) {
			var req = $services_util._get(api_point, filters);

			return req.then($services_util._handleSuccess, $services_util._handleError);
		},

		modify: function(method, sizechart) {
			var req = $services_util._modify(api_point, method, {
				sizechart: sizechart
			});

			return req.then($services_util._handleSuccess, $services_util._handleError);
		},

		delete: function(sizechart) {
			return this.modify("DELETE", sizechart);
		}
	});
});

api.service("$category", function($services_util) {

	var api_point = currentHost() + "/api/categories/";

	return ({

		get: function(filters) {
			var req = $services_util._get(api_point, filters);

			return req.then($services_util._handleSuccess, $services_util._handleError);
		},

		modify: function(method, node) {
			var req = $services_util._modify(api_point, method, {
				category: node
			});

			return req.then($services_util._handleSuccess, $services_util._handleError);
		},

		delete: function(node) {
			return this.modify("DELETE", node);
		}
	});
});