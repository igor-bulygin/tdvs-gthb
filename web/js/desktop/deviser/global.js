console.log("Global desktop deviser");
var global_deviser = angular.module('global-deviser', ['toastr']);

global_deviser.dataURItoBlob = function(dataURI) {
	var byteString;
	if (dataURI.split(',')[0].indexOf('base64') >= 0)
		byteString = atob(dataURI.split(',')[1]);
	else
		byteString = unescape(dataURI.split(',')[1]);

	// separate out the mime component
	var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];

	// write the bytes of the string to a typed array
	var ia = new Uint8Array(byteString.length);
	for (var i = 0; i < byteString.length; i++) {
		ia[i] = byteString.charCodeAt(i);
	}

	return new Blob([ia], {type:mimeString});
};

global_deviser.controller("cropCtrl", function($scope, $modalInstance, $timeout, data) {
	$scope.croppedphoto = "";

	var _reader = new FileReader();
	_reader.onloadend = function() {
		$scope.$apply(function() {
			$scope.photo = _reader.result;
		});
	};
	_reader.readAsDataURL(data.photo);

	$scope.ok = function() {
		var _f = global_deviser.dataURItoBlob($scope.croppedphoto);
		_f.name = $scope.photo.name;

		$modalInstance.close({
			"croppedphoto": _f
		});
	};

	$scope.cancel =  function() {
		$modalInstance.dismiss();
	};
});

var ngFileUpload = angular.module('ngFileUpload');
ngFileUpload.directive('ngfBgSrc', ['$parse', '$timeout', function ($parse, $timeout) {
	return {
		restrict: 'AE',
		link: function (scope, elem, attr) {
			if (window.FileReader) {
				scope.$watch(attr.ngfBgSrc, function (file) {
					if (file &&
						ngFileUpload.validate(scope, $parse, attr, file, null) &&
						(!window.FileAPI || navigator.userAgent.indexOf('MSIE 8') === -1 || file.size < 20000) &&
						(!window.FileAPI || navigator.userAgent.indexOf('MSIE 9') === -1 || file.size < 4000000)) {
						$timeout(function () {
							//prefer URL.createObjectURL for handling refrences to files of all sizes
							//since it doesn´t build a large string in memory
							var URL = window.URL || window.webkitURL;
							if (URL && URL.createObjectURL) {
								elem.css({
									'background-image': 'url(' + URL.createObjectURL(file) + ')'
								});
							} else {
								var fileReader = new FileReader();
								fileReader.readAsDataURL(file);
								fileReader.onload = function (e) {
									$timeout(function () {
										elem.css({
											'background-image': 'url(' + e.target.result + ')'
										});
									});
								};
							}
						});
					} else {
						if(attr.DefaultSrc === undefined) return;
						elem.css({
							'background-image': 'url(' + attr.ngfDefaultSrc + ')' || ''
						});
					}
				});
			}
		}
	};
}]);