(function() {

	"use strict";

	function controller(productService, personDataService, productDataService, languageDataService, metricDataService,
		UtilService, tagDataService, $rootScope, productEvents, sizechartDataService, $window, $timeout,$anchorScroll) {
		var vm = this;
		vm.categories_helper = [];
		vm.save = save;
		vm.saving = false;

		function init(){
			vm.from_edit = false;
			

			getLanguages();
			getTags();
			getDeviser();
			getMetric();
			getPaperType();
		}

		init();

		function getTags() {
			function onGetTagsSuccess(data) {
				vm.tags = angular.copy(data.items);
				getCategories();
			}

			tagDataService.getTags(null, onGetTagsSuccess, UtilService.onError);
		}

		function getCategories() {
			function onGetCategoriesSuccess(data) {
				vm.allCategories = data.items;
				getSizechart();
			}

			productDataService.getCategories({scope: 'all'}, onGetCategoriesSuccess, UtilService.onError);
		}


		function getMetric() {
			function onGetMetricSuccess(data) {
				vm.metric = angular.copy(data);
			}

			metricDataService.getMetric(null, onGetMetricSuccess, UtilService.onError);
		}

		function getSizechart() {
			function onGetSizechartSuccess(data) {
				vm.sizecharts = data.items;
				getProduct();
			}

			sizechartDataService.getDeviserSizechart({
				scope: 'all',
				deviser_id : person.short_id
			}, onGetSizechartSuccess, UtilService.onError);
		}

		function getPaperType() {
			function onGetPaperTypeSuccess(data) {
				vm.papertypes = data.items;
			}

			productDataService.getPaperType(null, onGetPaperTypeSuccess, UtilService.onError)
		}

		function getLanguages() {
			function onGetLanguagesSuccess(data) {
				vm.languages = data.items;
			}

			languageDataService.getLanguages(onGetLanguagesSuccess, UtilService.onError);
		}

		function getProduct() {
			function onGetProductPrivSuccess(data){
				//TODO refactorize
				vm.product = angular.copy(data);
				if (!vm.product.emptyCategory) {
					vm.product.emptyCategory = false;
				}
				if (!vm.product.slug) {
					vm.product.slug = {};
				}
				if (!vm.product.categories) {
					vm.product.categories = [];
				}
				if (!vm.product.media) {
					vm.product.media = {
						photos: [],
						description_photos: []
					};
				}
				if (!vm.product.faq) {
					vm.product.faq = [];
				}
				if (!vm.product.options) {
					vm.product.options = {};
				}
				if (!vm.product.madetoorder) {
					vm.product.madetoorder = {
						type: 0
					};
				}
				if (!vm.product.preorder) {
					vm.product.preorder = {
						type: 0
					};
				}
				if (!vm.product.bespoke) {
					vm.product.bespoke = {
						type: 0
					};
				}
				if (!vm.product.tags) {
					vm.product.tags = {};
				}
				if (!vm.product.price_stock) {
					vm.product.price_stock = [];
				}
				if (!vm.product.warranty || angular.isUndefined(vm.product.warranty.type) || vm.product.warranty.type == null) {
					vm.product.warranty = {type: 3};
				}
				if (!vm.product.returns || angular.isUndefined(vm.product.returns.type) || vm.product.returns.type == null) {
					vm.product.returns = {type: 1};
				}
				vm.from_edit = true;
				vm.product = productService.parseProductFromService(vm.product);
			}
			var params = {
				idProduct: product.short_id
			}
			productDataService.getProductPriv(params, onGetProductPrivSuccess, UtilService.onError);
		}
		
		function getDeviser(){
			function onGetProfileSuccess(data) {
				vm.deviser = angular.copy(data);
				vm.link_profile = '/deviser/' + data.slug + '/' + data.id + '/store/edit';
				vm.profile = currentHost()+vm.deviser.url_images+vm.deviser.media.profile_cropped;
			}

			personDataService.getProfile({
				personId: person.short_id
			}, onGetProfileSuccess, UtilService.onError);
		}

		function saved_draft() {
			var aux=vm.product.emptyCategory;
			vm.product = productService.parseProductFromService(vm.product);
			vm.product.emptyCategory=aux;
			vm.progressSaved = true;
			$timeout(() => {
				vm.progressSaved = false;
			}, 5000);
		}
		

		//publish is true when publishing the product
		function save(publish) {
			vm.saving = true;

			function onUpdateProductSuccess(data) {
				if(vm.product.product_state === 'product_state_draft' || !publish) {
					saved_draft();
					vm.disable_save_buttons=false;
					vm.saving = false;
				} else if (vm.product.product_state === 'product_state_active' && publish) {
					$window.location.href = currentHost() + vm.link_profile + '?published=true';
				}
			}

			function onUpdateProductError(err) {
				vm.disable_save_buttons=false;
				vm.errors = true;
				vm.saving = false;
				if(err.data.errors && err.data.errors.required && angular.isArray(err.data.errors.required)) {
					$rootScope.$broadcast(productEvents.requiredErrors, {required: err.data.errors.required});
				}
			}

			vm.disable_save_buttons = true;
			var required = [];
			if(publish)
				vm.product.product_state = 'product_state_active';

			//parse empty multilanguage fields
			UtilService.parseMultiLanguageEmptyFields(vm.product.name);
			UtilService.parseMultiLanguageEmptyFields(vm.product.description);
			//parse faq
			if(angular.isArray(vm.product.faq) && vm.product.faq.length > 0) {
				vm.product.faq.forEach(function(element) {
					UtilService.parseMultiLanguageEmptyFields(element.question);
					UtilService.parseMultiLanguageEmptyFields(element.answer);
				});
			}
			//validations
			if(vm.product.product_state !== 'product_state_draft') {
				required = productService.validate(vm.product, vm.tags);
				if (vm.product.emptyCategory) {
					required.push("emptyCategory");
				}
			} else {
				//check for null categories
				while(vm.product.categories.indexOf(null) > -1) {
					var pos = vm.product.categories.indexOf(null);
					vm.product.categories.splice(pos, 1);
				}
			}

			if(required.length === 0) {
				var aux=vm.product.emptyCategory;
				vm.product.warranty.value=parseInt(vm.product.warranty.value);
				vm.product.returns.value=parseInt(vm.product.returns.value);
				productDataService.updateProductPriv(vm.product, {
					idProduct: vm.product.id
				}, onUpdateProductSuccess, onUpdateProductError);
				vm.product.emptyCategory=aux;
			} else {
				vm.disable_save_buttons=false;
				vm.errors = true;
				vm.saving = false;
				$rootScope.$broadcast(productEvents.requiredErrors, {required: required});
				$anchorScroll(required[0]);
			}

		}

	}

	angular
		.module('product')
		.controller('editProductCtrl',controller);

}());