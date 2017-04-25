(function() {

	"use strict";

	function controller(productService, personDataService, productDataService, languageDataService, metricDataService,
		UtilService, tagDataService, $scope, $rootScope, productEvents, sizechartDataService, $window, $timeout) {
		var vm = this;
		vm.categories_helper = [];
		vm.save = save;

		function init(){
			vm.from_edit = false;
			vm.product = {};
			vm.product.slug = {};
			vm.product.categories = [];
			vm.product.media = {
				photos: [],
				description_photos: []
			};
			vm.product.faq = [];
			vm.product.options = {};
			vm.product.madetoorder = {
				type: 0
			};
			vm.product.preorder = {
				type: 0
			};
			vm.product.bespoke = {
				type: 0
			};
			vm.product.tags = {};
			vm.product.price_stock = [];

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

			metricDataService.getMetric(onGetMetricSuccess, UtilService.onError);
		}

		function getSizechart() {
			function onGetSizechartSuccess(data) {
				vm.sizecharts = data.items;
				getProduct();
			}

			sizechartDataService.getSizechart({
				scope: 'all'
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
				vm.product = angular.copy(data);
				vm.from_edit = true;
				vm.product_original = angular.copy(data);
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
			vm.product = productService.parseProductFromService(vm.product);
			vm.progressSaved = true;
			$timeout(() => {
				vm.progressSaved = false;
			}, 5000);
		}
		

		//publish is true when publishing the product, otherwise is false
		function save(publish) {
			function onUpdateProductSuccess(data) {
				vm.disable_save_buttons=false;
				if(vm.product.product_state === 'product_state_draft' || !publish) {
					saved_draft();
				} else if (vm.product.product_state === 'product_state_active' && publish) {
					$window.location.href = currentHost() + vm.link_profile + '?published=true';
				}
			}
			function onUpdateProductError(err) {
				vm.disable_save_buttons=false;
				vm.errors = true;
				if(err.data.errors && err.data.errors.required && angular.isArray(err.data.errors.required))
					$rootScope.$broadcast(productEvents.requiredErrors, {required: err.data.errors.required});
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
				required = productService.validate(vm.product);
			} else {
				//check for null categories
				while(vm.product.categories.indexOf(null) > -1) {
					var pos = vm.product.categories.indexOf(null);
					vm.product.categories.splice(pos, 1);
				}
			}

			if(required.length === 0) {
				productDataService.updateProductPriv(vm.product, {
					idProduct: vm.product.id
				}, onUpdateProductSuccess, onUpdateProductError);
			} else {
				vm.disable_save_buttons=false;
				vm.errors = true;
				$rootScope.$broadcast(productEvents.requiredErrors, {required: required});
			}

		}
	}

	angular
		.module('todevise')
		.controller('editProductCtrl',controller);

}());