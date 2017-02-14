(function () {
	"use strict";

	function lovedDataService($resource, apiConfig) {
		//priv
		this.LovedPriv = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'loved/:productId');

		//pub
		this.Loved = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'loved/:productId');

		this.setLoved = setLoved;
		this.deleteLoved = deleteLoved;

		function setLoved(data, success, error) {
			var loved = new this.LovedPriv;
			for(var key in data) {
				loved[key] = angular.copy(data[key]);
			}
			loved.$save()
				.then(function(dataSuccess) {
					success(dataSuccess)
				}, function (err) {
					error(err)
				});
		}

		function deleteLoved(params, success, error) {
			this.LovedPriv.delete(params).$promise.then(function(dataDeleted) {
				success(dataDeleted)
			}, function(err) {
				error(err);
			});
		}
	}

	angular
		.module('api')
		.service('lovedDataService', lovedDataService);

}());