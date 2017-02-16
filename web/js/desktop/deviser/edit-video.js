(function () {
	"use strict";

	function controller(deviserDataService, productDataService, toastr, UtilService, $window) {
		var vm = this;
		vm.addVideo = addVideo;
		vm.findProducts = findProducts;
		vm.selectProduct = selectProduct;
		vm.updateDeviserVideos = updateDeviserVideos;
		vm.deleteVideo = deleteVideo;
		vm.deleteTag = deleteTag;
		vm.showAddVideo = showAddVideo;
		vm.done = done;
		vm.works = [];
		//from https://gist.github.com/brunodles/927fd8feaaccdbb9d02b
		vm.YoutubeRegex = /(?:https?:\/\/)?(?:www\.)?youtu\.?be(?:\.com)?\/?.*(?:watch|embed)?(?:.*v=|v\/|\/)([\w\-_]+)\&?/i;

		init();

		function init() {
			getDeviser();
		}

		function getDeviser() {
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function (dataDeviser) {
				vm.deviser = dataDeviser;
				if (!vm.deviser.videos)
					vm.deviser.videos = [];
			})
		}

		function updateDeviserVideos(index) {
			//if it comes from rearrange
			if (index >= 0) {
				vm.deviser.videos.splice(index, 1);
				vm.works = [];
				vm.searchTerm = [];
			}
			var patch = new deviserDataService.Profile;
			patch.deviser_id = vm.deviser.id;
			patch.scenario = "deviser-update-profile";
			patch.videos = [];
			//set videos and product_id's
			vm.deviser.videos.forEach(function (element, vid_index) {
				patch.videos[vid_index] = {
					url: element.url,
					products: []
				}
				element.products.forEach(function (product) {
					patch.videos[vid_index].products.push(product.id)
				})
			})
			patch.$update().then(function (dataVideos) {
				$window.location.href = '/deviser/' + dataVideos.slug + '/' + dataVideos.id + '/video';
			}, function (err) {
				toastr.error(err);
				getDeviser();
			});
		}

		function findProducts(key, index) {
			function onGetProductPrivSuccess(data) {
				if(data.items.length === 0)
					vm.noProducts = true;
				vm.works[index] = data.items;
				vm.works[index].forEach(function(element) {
					element.url_image_preview = element.url_images + element.media.photos[0].name;
				})
			}

			vm.noProducts = false;
			vm.product_min_length = false;
			if (key.length < 4)
				vm.product_min_length = true;
			else {
				var params = {
					name: key
				}
				productDataService.getProductPriv(params, onGetProductPrivSuccess, UtilService.onError);
			}
		}

		function addVideo() {
			if (vm.url) {
				vm.deviser.videos.unshift({
					url: vm.url,
					products: []
				});
				delete vm.url;
			}
		}

		function selectProduct(index, work) {
			if (!vm.deviser.videos[index].products)
				vm.deviser.videos[index].products = [];
			vm.deviser.videos[index].products.push(work);
		}

		function deleteVideo(index) {
			vm.deviser.videos.splice(index, 1);
		}

		function deleteTag(video_index, tag_index) {
			vm.deviser.videos[video_index].products.splice(tag_index, 1);
		}

		function showAddVideo() {
			vm.addVideosClicked = true;
		}

		function done() {
			updateDeviserVideos();
		}
	}

	angular.module('todevise')
		.controller('editVideosCtrl', controller);

}());