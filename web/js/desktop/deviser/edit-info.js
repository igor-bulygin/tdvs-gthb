var todevise = angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'angular-multi-select', 'angular-img-dl', 'global-deviser', 'global-desktop', 'api', "ngFileUpload", "ngImgCrop"]);
var global_deviser = angular.module('global-deviser');

todevise.filter('arrpatch', [
	function () {
		return function (arr, word) {
			arr = JSON.parse(JSON.stringify(arr));
			for (var i = 0; i < arr.length; i++) {
				arr.splice(i, 0, word);
				i++;
			}
			return arr.join(", ");
		}
	}
]);

todevise.controller('deviserCtrl', ["$scope", "$timeout", "$deviser", "$deviser_util", "$category_util", "toastr", "$uibModal", "Upload", "$http", "$product", "$product_util", function($scope, $timeout, $deviser, $deviser_util, $category_util, toastr, $uibModal, Upload, $http, $product, $product_util) {
	$scope.lang = _lang;
	$scope.deviser = _deviser;
	$scope.headerphoto = [];
	$scope.profilephoto = [];

	//Sort by path length
	_categories = $category_util.sort(_categories);
	$scope.categories = $category_util.create_tree(_categories);

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

	$scope.crop_header = function() {
		var modalInstance = $uibModal.open({
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
		var modalInstance = $uibModal.open({
			templateUrl: 'template/modal/deviser/crop_circle.html',
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

	$scope.new_product = function() {
		var _product = $product_util.newProduct($scope.deviser.short_id);
		$product.modify("POST", _product).then(function(data) {
			window.location.href = window.location.origin + "/" + $scope.deviser.slug + "/edit-work/" + data.short_id + "/";
		}, function(err) {
			toastr.error("Couldn't create product!", err);
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
