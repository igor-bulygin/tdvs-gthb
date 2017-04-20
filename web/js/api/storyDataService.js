(function () {
	"use strict";

	function storyDataService($resource, apiConfig, apiMethods) {
		//pub
		var Story = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'story/:idStory');
		//priv
		var StoryPriv = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'story/:idStory', null, {
			'update': {
				method: 'PATCH'
			}
		});

		//functions
		this.getStoryPub = getStoryPub;
		this.getStoryPriv = getStoryPriv;
		this.createStory = createStory;
		this.updateStory = updateStory;
		this.deleteStory = deleteStory;

		function getStoryPub(params, onSuccess, onError) {
			apiMethods.get(Story, params, onSuccess, onError);
		}

		function getStoryPriv(params, onSuccess, onError) {
			apiMethods.get(StoryPriv, params, onSuccess, onError);
		}

		function createStory(data, onSuccess, onError) {
			apiMethods.create(StoryPriv, data, null, onSuccess, onError);
		}

		function updateStory(data, params, onSuccess, onError) {
			apiMethods.update(StoryPriv, data, params, onSuccess, onError);
		}

		function deleteStory(params, onSuccess, onError) {
			apiMethods.deleteItem(StoryPriv, params, onSuccess, onError);
		}
	}

	angular
		.module('api')
		.service('storyDataService', storyDataService);
}());