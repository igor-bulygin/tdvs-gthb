(function () {
	"use strict";

	function controller(personDataService, productDataService, toastr, UtilService, $window) {
		var vm = this;
		vm.addVideo = addVideo;
		vm.findProducts = findProducts;
		vm.selectProduct = selectProduct;
		vm.updatePersonVideos = updatePersonVideos;
		vm.deleteVideo = deleteVideo;
		vm.deleteTag = deleteTag;
		vm.showAddVideo = showAddVideo;
		vm.done = done;
		vm.works = [];
		//from https://gist.github.com/brunodles/927fd8feaaccdbb9d02b
		vm.YoutubeRegex = /(?:https?:\/\/)?(?:www\.)?youtu\.?be(?:\.com)?\/?.*(?:watch|embed)?(?:.*v=|v\/|\/)([\w\-_]+)\&?/i;

		init();

		function init() {
			getPerson();
		}

		function getPerson() {
			function onGetProfileSuccess(data) {
				vm.person = angular.copy(data);
				if(!vm.person.videos)
					vm.person.videos = [];
				else if(angular.isArray(vm.person.videos) && vm.person.videos.length > 0) {
					vm.person.videos.map(function(element) {
						element.products.map(function(product, index) {
							function onProductDataSuccess(data) {
								element.products[index] = Object.assign({}, data);
							}
							productDataService.getProductPub({
								idProduct: product
							}, onProductDataSuccess, UtilService.onError);
						})
					})
				}
			}

			personDataService.getProfile({
				personId: person.short_id
			}, onGetProfileSuccess, UtilService.onError);
		}

		function updatePersonVideos(index) {
			function onUpdateVideosSuccess(data) {
				$window.location.href = '/deviser/' + data.slug + '/' + data.id + '/video';
			}

			function onUpdateVideosError(err) {
				UtilService.onError(err);
				getPerson();
			}

			//if it comes from rearrange
			if (index >= 0) {
				vm.person.videos.splice(index, 1);
				vm.works = [];
				vm.searchTerm = [];
			}
			var data = {
				videos: []
			}

			//set videos and product_id's
			vm.person.videos.forEach(function (element, index) {
				data.videos.push({
					url: element.url,
					products: []
				});

				element.products.forEach(function (product) {
					data.videos[index].products.push(product.id)
				})
			})

			personDataService.updateProfile(data, {
				personId: person.short_id
			}, onUpdateVideosSuccess, onUpdateVideosError);
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
				vm.person.videos.unshift({
					url: vm.url,
					products: []
				});
				delete vm.url;
			}
		}

		function selectProduct(index, work) {
			if (!vm.person.videos[index].products)
				vm.person.videos[index].products = [];
			vm.person.videos[index].products.push(work);
		}

		function deleteVideo(index) {
			vm.person.videos.splice(index, 1);
		}

		function deleteTag(video_index, tag_index) {
			vm.person.videos[video_index].products.splice(tag_index, 1);
		}

		function showAddVideo() {
			vm.addVideosClicked = true;
		}

		function done() {
			updatePersonVideos();
		}
	}

	angular.module('todevise')
		.controller('editVideosCtrl', controller);

}());