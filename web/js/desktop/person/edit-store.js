(function () {
	"use strict";

	function controller(productDataService, personDataService, UtilService, $location, $uibModal, $timeout) {
		var vm = this;
		vm.truncateString = UtilService.truncateString;
		vm.sortableOptions = {
			update: function(e, ui) {
				update(ui.item.sortable.dropindex, ui.item.sortable.model);
			}
		}
		vm.update = update;
		vm.open_modal_delete = open_modal_delete;
		vm.show_unpublished_works = show_unpublished_works;
		vm.selected_language=_lang;
		vm.language = vm.selected_language;

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
				})
			}
			var params = {
				"deviser": person.short_id,
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
			function onGetProfileSuccess(data) {
				vm.deviser = angular.copy(data);
				getProducts();
			}

			personDataService.getProfile({
				personId: person.short_id
			}, onGetProfileSuccess, UtilService.onError);
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
				productDataService.updateProductPriv({
					position: index+1
				},{
					idProduct: product.id
				}, onUpdateProductSuccess, UtilService.onError);
			}
		}

		function setMinimumPrice(product) {
			if(angular.isArray(product.price_stock) && product.price_stock.length > 0) {
				var array_available = product.price_stock.map(function(element) {
					if(element.available)
						return element.price;
				});
				product.min_price = array_available.reduce((prev, value) => prev < value ? prev : value);
			} else {
				product.min_price = '-'
			}
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
		.module('person')
		.controller('editStoreCtrl', controller)
		.controller('modalDeleteProductCtrl', modalController)
		.filter('draftProduct',draftProduct)
		.filter('publishedProduct', publishedProduct);

}());