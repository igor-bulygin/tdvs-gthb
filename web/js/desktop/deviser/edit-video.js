(function () {
	"use strict";

	function controller(deviserDataService, productDataService, toastr, UtilService) {
		var vm = this;
		vm.addVideo = addVideo;
		vm.findProducts = findProducts;
		vm.selectProduct = selectProduct;
		vm.updateDeviserVideos = updateDeviserVideos;
		vm.works = [];

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
			if (index) {
				vm.deviser.videos.splice(index, 1);
			}
			var patch = new deviserDataService.Profile;
			patch.deviser_id = vm.deviser.id;
			patch.scenario = "deviser-videos-update";
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
			});
		}

		function findProducts(key, index) {
			productDataService.Product.get({
				name: key
			}).$promise.then(function (dataProducts) {
				vm.works[index] = dataProducts.items;
				vm.works[index].forEach(function (element) {
					element.url_image_preview = element.url_images + element.media.photos[0].name;
				})

			}, function (err) {
				toastr.error(err);
			});
		}

		function addVideo() {
			if (vm.url) {
				vm.deviser.videos.unshift({
					url: vm.url,
					products: []
				});
				delete vm.url;
				updateDeviserVideos();
			}
		}

		function selectProduct(index, work) {
			if (!vm.deviser.videos[index].products)
				vm.deviser.videos[index].products = [];
			vm.deviser.videos[index].products.push(work);
			updateDeviserVideos();
		}
	}

	angular.module('todevise', ['api', 'toastr', 'dndLists', 'util', 'ngYoutubeEmbed'])
		.controller('editVideosCtrl', controller);

}());