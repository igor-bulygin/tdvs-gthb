var todevise = angular.module('todevise', ['ui.bootstrap', 'angular-multi-select', 'global-deviser', 'global-desktop', 'api', "ngFileUpload", "ngImgCrop"]);
var global_deviser = angular.module('global-deviser');

todevise.controller('deviserCtrl', ["$scope", "$timeout", "$deviser", "$deviser_util", "toastr", "$modal", "Upload", function($scope, $timeout, $deviser, $deviser_util, toastr, $modal, Upload) {
	$scope.lang = _lang;
	$scope.deviser = _deviser;
	$scope.api = {};

	$scope.crop_profile = function() {

		var modalInstance = $modal.open({
			templateUrl: 'template/modal/global/crop.html',
			controller: 'cropCtrl',
			resolve: {
				data: function () {
					return {
						'photo': $scope.profilephoto[0]
					}
				}
			}
		});

		modalInstance.result.then(function(data) {
			$scope.profilephoto = [data.croppedphoto];
		}, function () {
			//Cancel
		});
	};

	$scope.cancel = function() {
		$scope.type_watch_paused = true;
		$scope.deviser = angular.copy($scope._shadow);

		$timeout(function() {
			$scope.type_watch_paused = false;
		}, 0);
	};

	$scope.save = function() {
		$tag.modify("POST", $scope.tag).then(function() {
			toastr.success("Tag saved successfully!");
		}, function(err) {
			toastr.error("Failed saving tag!", err);
		});
	};

	$scope._shadow = angular.copy($scope.deviser);
}]);