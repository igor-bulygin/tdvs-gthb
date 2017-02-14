(function () {
	"use strict";

	function lovedDataService($resource, apiConfig) {
		//priv
		var LovedPriv = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'loved/:productId');
		//pub
		var Loved = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'loved/:productId');

		this.setLoved = setLoved;
		this.deleteLoved = deleteLoved;

		function setLoved(data, onsuccess, onerror) {
			var loved = new LovedPriv;
			for(var key in data) {
				loved[key] = angular.copy(data[key]);
			}
			loved.$save()
				.then(function(dataSuccess) {
					onsuccess(dataSuccess)
				}, function (err) {
					onerror(err)
				});
		}

		function deleteLoved(params, onsuccess, onerror) {
			LovedPriv.delete(params).$promise.then(function(dataDeleted) {
				onsuccess(dataDeleted)
			}, function(err) {
				onerror(err);
			});
		}
	}

	angular
		.module('api')
		.service('lovedDataService', lovedDataService);

}());