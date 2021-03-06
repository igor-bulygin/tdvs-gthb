(function () {
	"use strict";

	function lovedDataService($resource, apiConfig, apiMethods) {
		//priv
		var LovedPriv = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'loved/:productId');
		var LovedPost = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'loved/post/:postId');
		var LovedTimeline = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'loved/timeline/:timelineId');
		

		//pub
		var Loved = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'loved/:productId');


		this.setLoved = setLoved;
		this.deleteLoved = deleteLoved;
		this.deleteLovedPost = deleteLovedPost;
		this.deleteLovedTimeline = deleteLovedTimeline;

		function setLoved(data, onSuccess, onError) {
			apiMethods.create(LovedPriv, data, null, onSuccess, onError);
		}

		function deleteLoved(params, onSuccess, onError) {
			apiMethods.deleteItem(LovedPriv, params, onSuccess, onError)
		}

		function deleteLovedPost(params, onSuccess, onError) {
			apiMethods.deleteItem(LovedPost, params, onSuccess, onError)
		}

		function deleteLovedTimeline(params, onSuccess, onError) {
			apiMethods.deleteItem(LovedTimeline, params, onSuccess, onError)
		}


	}

	angular
		.module('api')
		.service('lovedDataService', lovedDataService);

}());