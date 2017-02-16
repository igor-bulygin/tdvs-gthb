(function () {
	"use strict";

	function controller(productDataService, deviserDataService, UtilService, toastr, $location, $uibModal, $timeout) {
		var vm = this;
		vm.update = update;
		vm.open_modal_delete = open_modal_delete;
		vm.deviser_id = UtilService.returnDeviserIdFromUrl();
		vm.show_unpublished_works = show_unpublished_works;
		vm.language = 'en-US';

		function init() {
			parseCategories();
			getDeviser();
		}

		init();

		function getProducts() {
			function onGetProductsSuccess(data) {
				vm.products = data.items;
				vm.products.forEach(function(element) {
					setMinimumPrice(element);
					element.edit_link = currentHost() + '/deviser/' + vm.deviser.slug + '/' + vm.deviser.id + '/works/' + element.id + '/edit';
					element.link = currentHost() + '/work/' + element.slug + '/' + element.id;
				})
				parseMainPhoto(vm.products);
			}
			var params = {
				"deviser": UtilService.returnDeviserIdFromUrl(),
				"limit": 1000
			}
			if(vm.subcategory || vm.category) {
				if(vm.category !== 'product_state_draft') {
					params["categories[]"] = [];
					if(vm.subcategory)
						params["categories[]"].push(vm.subcategory);
					if(vm.category)
						params["categories[]"].push(vm.category);
				}
			}

			productDataService.getProductPriv(params, onGetProductsSuccess, UtilService.onError);
		}

		function getDeviser() {
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function(dataDeviser) {
				vm.deviser = dataDeviser;
				getProducts();
			}, function(err) {
				//errors
			});
		}

		function parseCategories() {
			//get category in vm.category and subcategory in vm.subcategory
			var url = $location.absUrl();
			if(url.split("?").length > 1) {
				var queries = url.split("?")[1];
				var params = queries.split('&');
				var params_obj = {};
				params.forEach(function (element) {
					var item = element.split('=')
					params_obj[item[0]] = item[1];
				})
				for(var key in params_obj) {
					switch (key) {
						case 'published':
							vm.view_published_topbar = params_obj[key];
							$timeout(function(){
								vm.view_published_topbar = false;
							}, 10000);
							break;
						case 'product_state':
							if(params_obj[key] === 'product_state_draft')
								vm.view_unpublished_works = true;
							break;
						case 'category':
							vm.category = params_obj[key];
							break;
						case 'subcategory':
							vm.subcategory = params_obj[key];
							break;
					}
				}
			}
		}

		function update(index, product) {
			function onUpdateProductSuccess(data) {
				getProducts();
			}

			if (index >= 0) {
				vm.products.splice(index, 1);
			}
			var pos = -1;
			for (var i = 0; i < vm.products.length; i++) {
				if (product.id === vm.products[i].id)
					pos = i;
			}
			if (pos > -1) {
				var position = (pos + 1);
				productDataService.updateProductPriv({
					position: position
				},{
					idProduct: product.id
				}, onUpdateProductSuccess, UtilService.onError);
			} else {
				toastr.error("Cannot be updated!");
			}
		}

		function setMinimumPrice(product) {
			var min_price;
			if(angular.isArray(product.price_stock) &&
				product.price_stock.length > 0) {
				min_price = product.price_stock[0].price;
				for(var i = 0; i < product.price_stock.length; i++) {
					if(product.price_stock[i].price < min_price) {
						min_price = product.price_stock[i].price;
					}
				}
				product.min_price = min_price;
			}else{
				product.min_price = '-';
			}
		}

		function parseMainPhoto(products) {
			products.forEach(function (element) {
					for (var i = 0; i < element.media.photos.length; i++) {
						if (element.media.photos[i].main_product_photo)
							element.main_photo = currentHost() + element.url_images + element.media.photos[i].name;
					}
				});
		}

		function open_modal_delete(id) {
			vm.id_to_delete = id;
			var modalInstance = $uibModal.open({
				templateUrl: 'modalDeleteProduct.html',
				controller: 'modalDeleteProductCtrl',
				controllerAs: 'modalDeleteProductCtrl'
			})

			modalInstance.result.then(function () {
				deleteProduct(vm.id_to_delete);
			});
		}

		function deleteProduct(id) {
			function onDeleteProductPrivSuccess(data) {
				getProducts();
			}
			
			productDataService.deleteProductPriv({
				idProduct: id
			}, onDeleteProductPrivSuccess, UtilService.onError)
		}

		function show_unpublished_works(){
			vm.view_unpublished_works = true;
		}

	}

	function draftProduct() {
		return function(input){
			var draft=[];
			angular.forEach(input,function(product){
				if(product.product_state === 'product_state_draft')
					draft.push(product)
				})
			return draft;
		}
	}

	function publishedProduct(){
		return function(input){
			var draft=[];
			angular.forEach(input,function(product){
				if(product.product_state === 'product_state_active' || product.product_state === null)
					draft.push(product)
			});
			return draft;
		}
	}

	function modalController($uibModalInstance) {
		var vm = this;
		vm.close = close;
		vm.ok = ok;

		function close() {
			$uibModalInstance.dismiss('cancel');
		}

		function ok() {
			$uibModalInstance.close();
		}
	}

	angular
		.module('todevise')
		.controller('editStoreCtrl', controller)
		.controller('modalDeleteProductCtrl', modalController)
		.filter('draftProduct',draftProduct)
		.filter('publishedProduct', publishedProduct);

}());