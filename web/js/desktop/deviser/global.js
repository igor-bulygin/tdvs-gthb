(function () {
	"use strict";

	// console.log("Global desktop deviser");

	function cropCtrl($scope, $uibModalInstance, $timeout, data) {
		$scope.croppedphoto = "";
		$scope.photo = data.photo;

		$scope.ok = function () {
			var _f = global_deviser.dataURItoBlob($scope.croppedphoto);
			_f.name = $scope.photo.name;

			$uibModalInstance.close({
				"croppedphoto": _f
			});
		};

		$scope.cancel = function () {
			$uibModalInstance.dismiss();
		}
	}

	var global_deviser = angular.module('global-deviser', ['toastr'])
		.controller('cropCtrl', cropCtrl);

	global_deviser.dataURItoBlob = function (dataURI) {
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

		return new Blob([ia], {
			type: mimeString
		});
	};

}());