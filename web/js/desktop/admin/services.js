var todevise = angular.module('todevise');

todevise.service("$category", function($http, $q) {
	return ({

		_handleSuccess: function(response) {
			return response.data;
		},

		_handleError: function(response) {
			if (!angular.isObject(response.data) || !response.data.message) {
				return $q.reject("An unknown error ocurried.");
			}
			return $q.reject(response.data.message);
		},

		get: function() {
			var req = $http({
				method: "get",
				headers: {
					'X-Requested-With': 'XMLHttpRequest'
				},
				url: currentURL()
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
				url: currentURL(),
				data: {
					category: node,
					_csrf: csrf
				}
			});

			return req.then(this._handleSuccess, this._handleError);
		}
	});
});