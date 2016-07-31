var todevise = angular.module('todevise', []);

/*
 * This manage show/hide text on faq section
 */



todevise.controller('becomeCtrl', ['$scope', '$cacheFactory', function ($scope, $cacheFactory) {

	$scope.portfolio_links = [{link:''}];
	$scope.video_links =  [{link:''}];

	$scope.newVideoLink = function(){
		var newItem = {link:''};
		$scope.video_links.push(newItem);
	}

	$scope.newPortFolioLink = function(){
		var newItem = {link:''};
		$scope.portfolio_links.push(newItem);
	}
}]);
