var todevise = angular.module('todevise', ['ui.bootstrap', 'angular-multi-select', 'global-admin', 'global-desktop', 'api']);

todevise.run(["$http", function($http) {
	$http.defaults.headers.get = {
		"X-Requested-With":  "XMLHttpRequest"
	};
}]);

todevise.config(['$provide', function($provide) {
	$provide.decorator('$browser', ['$delegate', function ($delegate) {
		$delegate.onUrlChange = function() {};
		$delegate.url = function() { return ""; };
		return $delegate;
	}]);
}]);

todevise.controller('tagCtrl', ["$scope", "$timeout", "$tag", "$tag_util", "$category_util", "toastr", "$modal", "$http", function($scope, $timeout, $tag, $tag_util, $category_util, toastr, $modal, $http) {
	$scope.lang = _lang;
	$scope.tag = _tag;

	//Sort by path length
	$category_util.sort(_categories);

	$scope.categories = $category_util.create_tree(_categories);


}]);
