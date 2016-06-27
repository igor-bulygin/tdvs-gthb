var todevise = angular.module('todevise', []);

/*
 * This manage show/hide text on terms section
 */


 //TODO: $cacheFactory needed?
todevise.controller('termCtrl', ['$scope', '$cacheFactory', function ($scope, $cacheFactory) {

	$scope.groupOfTerms = _groupOfTerms;
	$scope.activeTermId = $scope.groupOfTerms[0].short_id; //activate first group as default

	$scope.showTerms = function (activeTermId){
		$scope.activeTermId = activeTermId;
	}

}]);
