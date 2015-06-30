var todevise = angular.module('todevise', ['ui.bootstrap', 'angular-multi-select', 'global-deviser', 'global-desktop', 'api', "ngFileUpload", "ngImgCrop"]);
var global_deviser = angular.module('global-deviser');

todevise.controller('deviserCtrl', ["$scope", "$timeout", "$deviser", "$deviser_util", "toastr", "$modal", "Upload", function($scope, $timeout, $deviser, $deviser_util, toastr, $modal, Upload) {
	$scope.lang = _lang;
	$scope.api = {};

	$scope.crop_profile = function() {

		var modalInstance = $modal.open({
			templateUrl: 'template/modal/deviser/crop_profile.html',
			controller: 'crop_profileCtrl',
			resolve: {
				data: function () {
					return {
						'profilephoto': $scope.profilephoto[0]
					}
				}
			}
		});

		modalInstance.result.then(function(data) {
			$scope.profilephoto = [data.cropped];
		}, function () {
			//Cancel
		});
	};

	$scope.cancel = function() {
		$scope.type_watch_paused = true;
		$scope.tag = angular.copy($scope._shadow);

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

	$scope._shadow = angular.copy($scope.tag);
}]);

todevise.controller("crop_profileCtrl", function($scope, $modalInstance, $timeout, data) {
	$scope.cropped = "";

	var _reader = new FileReader();
	_reader.onloadend = function() {
		$scope.$apply(function() {
			console.log(_reader.result);
			$scope.profilephoto = _reader.result;
		});
	};
	_reader.readAsDataURL(data.profilephoto);

	$scope.ok = function() {
		console.log($scope.cropped);
		var _f = global_deviser.dataURLtoBlob($scope.cropped);
		_f.name = $scope.profilephoto.name;

		$modalInstance.close({
			"cropped": _f
		});
	};

	$scope.cancel =  function() {
		$modalInstance.dismiss();
	};
});
