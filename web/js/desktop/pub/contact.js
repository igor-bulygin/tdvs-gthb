var todevise = angular.module('todevise', []);

/*
 * This manage show/hide text on faq section
 */



todevise.controller('contactCtrl', ['$scope', '$cacheFactory', function ($scope, $cacheFactory) {

	console.log(_faqs);
	$scope.faqs = _faqs;

	$scope.debugit = function (key){
			console.log(key);
	}

	$scope.changed = function (){
			console.log($scope.selected);
			if ($scope.selected  == 'a'){
				$scope.orderShow = true;
			}
	}

}]);