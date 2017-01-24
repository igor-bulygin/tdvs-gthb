(function() {

	"use strict";

	function controller(productService, deviserDataService, productDataService, languageDataService, metricDataService, toastr, 
		UtilService, tagDataService, $scope, $rootScope, productEvents, sizechartDataService, $window) {
		var vm = this;
		vm.categories_helper = [];
		vm.save = save;

		function init(){
			vm.product = new productDataService.ProductPriv();
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
		}

		init();

		function getTags() {
			tagDataService.Tags.get()
				.$promise.then(function (dataTags) {
					vm.tags = dataTags.items;
					getCategories();
				}, function (err) {
					//err
				});
		}
		
		function getCategories() {
			productDataService.Categories.get({scope: 'all'})
				.$promise.then(function (dataCategories) {
					vm.allCategories = dataCategories.items;
					getMetric();
					getSizechart();
					getPaperType();
					getProduct();
				}, function(err) {
					//errors
				});
		}


		function getMetric() {
			metricDataService.Metric.get()
				.$promise.then(function (dataMetric) {
					vm.metric = dataMetric;
				}, function(err) {
					//errors
				})
		}

		function getSizechart() {
			sizechartDataService.Sizechart.get({scope: 'all'})
				.$promise.then(function (dataSizechart) {
					vm.sizecharts = dataSizechart.items;
				}, function (err) {
					//error
				});
		}

		function getPaperType() {
			productDataService.PaperType.get()
				.$promise.then(function (dataPaperType) {
					vm.papertypes = dataPaperType.items;
				}, function (err) {
					//errors
				});
		}

		function getLanguages() {
			languageDataService.Languages.get()
				.$promise.then(function(dataLanguages) {
					vm.languages = dataLanguages.items;
				}, function(err) {
					//errors
				});
		}

		function getProduct() {
			productDataService.ProductPriv.get({
				idProduct: UtilService.returnProductIdFromUrl()
			}).$promise.then(function(dataProduct) {
				vm.product = dataProduct;
				vm.product_original = angular.copy(dataProduct);
				vm.product = productService.parseProductFromService(vm.product);
				vm.product.from_edit = true;
			}, function (err) {
				//err
			});
		}
		
		function getDeviser(){
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function(dataDeviser) {
				vm.deviser = dataDeviser;
				vm.link_profile = '/deviser/' + dataDeviser.slug + '/' + dataDeviser.id + '/store/edit';
				vm.profile = currentHost()+vm.deviser.url_images+vm.deviser.media.profile_cropped;
			}, function(err) {
				//errors
			});
		}
		

		function save(state) {
			vm.disable_save_buttons = true;
			var required = [];
			vm.product.product_state = angular.copy(state);

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
				vm.product.$update({
					idProduct: vm.product.id
				}).then(function (dataSaved) {
					vm.disable_save_buttons=false;
					if(state === 'product_state_draft') {
						vm.product = productService.parseProductFromService(vm.product);
						toastr.success('Saved!');
					} else if (state === 'product_state_active') {
						$window.location.href = currentHost() + vm.link_profile + '?published=true';
					}
				}, function (err) {
					vm.disable_save_buttons=false;
					vm.errors = true;
					if(err.data.errors && err.data.errors.required && angular.isArray(err.data.errors.required))
						$rootScope.$broadcast(productEvents.requiredErrors, {required: err.data.errors.required});
				});
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