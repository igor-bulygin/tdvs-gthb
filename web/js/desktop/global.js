console.log("Global desktop");
var global_desktop = angular.module('global-desktop', []);

global_desktop.run(["$http", function($http) {
	$http.defaults.headers.get = {
		"X-Requested-With":  "XMLHttpRequest"
	};
}]);

/*
 * Takes an array of values and a word and returns a string
 * with that word prepended before each of the values.
 *
 * Example:
 * Input  -> ([1, 2, 3], 'foo')
 * Output -> "foo, 1, foo, 2, foo, 3"
 */
global_desktop.filter('arrpatch', [
	function () {
		return function (arr, word) {
			if (!Array.isArray(arr)) return "";
			arr = JSON.parse(JSON.stringify(arr));
			for (var i = 0; i < arr.length; i++) {
				arr.splice(i, 0, word);
				i++;
			}
			return arr.join(", ");
		}
	}
]);

global_desktop.factory("$global_desktop_util", function($http, $q) {
	var global_desktop_helpers = {};
	var global_point = currentHost() + "/global/";

	global_desktop_helpers._handleSuccess = function(response) {
		return response.data;
	};

	global_desktop_helpers._handleError = function(response) {
		if (!angular.isObject(response.data) || !response.data.message) {
			return $q.reject("An unknown error occurred.");
		}
		return $q.reject(response.data.message);
	};

	global_desktop_helpers.setFlash = function(key, message) {
		var req = $http({
			method: "get",
			url: global_point + "set-flash/?key=" + key + "&message=" + encodeURIComponent(message)
		});

		return req.then(this._handleSuccess, this._handleError);
	};

	global_desktop_helpers.getFlashes = function() {
		var req = $http({
			method: "get",
			url: global_point + "get-flashes/"
		});

		return req.then(this._handleSuccess, this._handleError);
	};

	return global_desktop_helpers;
});

global_desktop.run(function($rootScope, $global_desktop_util, toastr) {
	$global_desktop_util.getFlashes(false).then(function(data) {
		angular.forEach(JSON.parse(data), function(v, k) {
			toastr.info(v);
		});
	}, function(err) {
		//
	});
});
