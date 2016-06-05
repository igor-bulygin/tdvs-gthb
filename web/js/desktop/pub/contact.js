var todevise = angular.module('todevise', []);

/*
 * This manage show/hide text on faq section
 */



todevise.controller('contactCtrl', ['$scope', '$cacheFactory', function ($scope, $cacheFactory) {

	//temporal contact options
	$scope.formData = {};
	$scope.formData.dropDownSelected = -1;
	$scope.formData.selectedText = "What is your question about;";
	$scope.formData.dropDownOptions = {
		"0" : "Question about something",
		"1" : "Question about another thinkg"
	};

	$scope.selectOption = function (key){
		$scope.formData.dropDownSelected = key;
		$scope.showedDropdown = false;
		$scope.formData.selectedText = $scope.formData.dropDownOptions[ $scope.formData.dropDownSelected ];
	}

}]);
