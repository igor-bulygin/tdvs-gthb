var todevise = angular.module('todevise', ['ui.bootstrap', 'angular-multi-select', 'global-deviser', 'global-desktop', 'api', "ngFileUpload", "ngImgCrop"]);
var global_deviser = angular.module('global-deviser');

todevise.controller('deviserCtrl', ["$scope", "$timeout", "$deviser", "$deviser_util", "$category_util", "toastr", "$modal", "Upload", function($scope, $timeout, $deviser, $deviser_util, $category_util, toastr, $modal, Upload) {
	$scope.lang = _lang;
	$scope.deviser = _deviser;
	$scope.api = {};
	$scope.api_cat = {};
	$scope.headerphoto = [];
	$scope.profilephoto = [];
	$scope.countries = _countries;
	$scope.countries_lookup = _countries_lookup;

	//Sort by path length
	$category_util.sort(_categories);
	$scope.categories = $category_util.create_tree(_categories);

	$timeout(function() {
		$scope.api.select($scope.deviser.personal_info.country);

		angular.forEach($scope.deviser.categories, function(id) {
			$scope.api_cat.select(id);
		});

		$scope.$watch("selectedCategories", function(_new, _old) {
			if(_new !== _old) {
				$scope.deviser.categories = [];
				var _selected_categories = jsonpath.query(_new, "$..[?(!@.sub && @.short_id)]");
				angular.forEach(_selected_categories, function(_cat) {
					$scope.deviser.categories.push(_cat.short_id);
				});
			}
		});
	}, 0);

	if(_header_photo_base64 !== "") {
		var _blob = global_deviser.dataURItoBlob(_header_photo_base64);
		$scope.headerphoto.push(_blob);
	}

	if(_profile_photo_base64 !== "") {
		var _blob = global_deviser.dataURItoBlob(_profile_photo_base64);
		$scope.profilephoto.push(_blob);
	}

	$scope.$watch("headerphoto", function(n, o) {
		//Restore old profile picture if select dialog is canceled
		if(n === null) {
			$scope.headerphoto = o;
		}
	});

	$scope.$watch("profilephoto", function(n, o) {
		//Restore old profile picture if select dialog is canceled
		if(n === null) {
			$scope.profilephoto = o;
		}
	});

	$scope.$watch("selected_country", function(_new, _old) {
		if(_new == _old || _new.length === 0) return;
		$scope.deviser.personal_info.country = _new[0].country_code;
	});

	$scope.crop_header = function() {
		var modalInstance = $modal.open({
			templateUrl: 'template/modal/deviser/crop.html',
			controller: 'cropCtrl',
			resolve: {
				data: function () {
					return {
						'photo': $scope.headerphoto[0]
					}
				}
			}
		});

		modalInstance.result.then(function(data) {
			$scope.headerphoto = [data.croppedphoto];
		}, function () {
			//Cancel
		});
	};

	$scope.crop_profile = function() {
		var modalInstance = $modal.open({
			templateUrl: 'template/modal/deviser/crop.html',
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

	$scope.save = function() {

		$deviser.modify("POST", $scope.deviser).then(function() {
			toastr.success("Deviser saved successfully!");
		}, function(err) {
			toastr.error("Failed saving deviser!", err);
		});

		if($scope.headerphoto && $scope.headerphoto.length === 1) {
			Upload.upload({
				headers : {
					'X-CSRF-TOKEN' : yii.getCsrfToken()
				},
				url: _upload_header_photo_url,
				file: $scope.headerphoto[0]
			}).progress(function(e) {
				//var progressPercentage = parseInt(100.0 * e.loaded / e.total);
				//console.log('progress: ' + progressPercentage + '% ' + e.config.file.name);
			}).success(function(data, status, header, config) {
				//console.log('file ' + config.file.name + 'uploaded. Response: ' + data);
				$scope.deviser = data;
				toastr.success("Uploaded successfully headerphoto photo", config.file.name);
			}).error(function(err) {
				toastr.error("Error while uploading headerphoto photo", err)
			});
		}

		if($scope.profilephoto && $scope.profilephoto.length === 1) {
			Upload.upload({
				headers : {
					'X-CSRF-TOKEN' : yii.getCsrfToken()
				},
				url: _upload_profile_photo_url,
				file: $scope.profilephoto[0]
			}).progress(function(e) {
				//var progressPercentage = parseInt(100.0 * e.loaded / e.total);
				//console.log('progress: ' + progressPercentage + '% ' + e.config.file.name);
			}).success(function(data, status, header, config) {
				//console.log('file ' + config.file.name + 'uploaded. Response: ' + data);
				$scope.deviser = data;
				toastr.success("Uploaded successfully profile photo", config.file.name);
			}).error(function(err) {
				toastr.error("Error while uploading profile photo", err)
			});
		}
	};
}]);