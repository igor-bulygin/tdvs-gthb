(function () {
	"use strict";

	function controller(deviserDataService, productDataService, toastr, UtilService) {
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
		vm.YoutubeRegex = /http(?:s?):\/\/(?:www\.)?youtu(?:be\.com\/watch\?v=|\.be\/)([\w\-\_]*)(&(amp;)?[\w\?=]*)?/;

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
				//nothing
			}, function (err) {
				toastr.error(err);
				getDeviser();
			});
		}

		function findProducts(key, index) {
			vm.noProducts = false;
			vm.product_min_length = false;
			if (key.length < 4)
				vm.product_min_length = true;
			else {
				productDataService.Product.get({
					name: key
				}).$promise.then(function (dataProducts) {
					if (dataProducts.items.length === 0)
						vm.noProducts = true;
					vm.works[index] = dataProducts.items;
					vm.works[index].forEach(function (element) {
						element.url_image_preview = element.url_images + element.media.photos[0].name;
					})

				}, function (err) {
					toastr.error(err);
				});
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