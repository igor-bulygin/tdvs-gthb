(function () {
	"use strict";

	function uploadDataService(Upload, apiConfig) {
		var Uploads = apiConfig.baseUrl + 'priv/' + apiConfig.version + 'uploads';

		this.UploadFile = UploadFile;

		function UploadFile(data, onSuccess, onError, onUploading) {
			Upload.upload({
				url: Uploads,
				data: data
			}).then(function(returnData) { onSuccess(returnData)}, function(err) { onError(err); }, function(evt) { onUploading(evt)} );
		}
	}

	angular.module('api')
		.service('uploadDataService', uploadDataService);

}());