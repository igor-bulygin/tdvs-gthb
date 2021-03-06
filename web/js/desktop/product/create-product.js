(function() {

	function controller($scope,personDataService, metricDataService, sizechartDataService,
		productDataService, languageDataService, toastr, UtilService, productService,
		localStorageService, tagDataService, productEvents, $rootScope, $window, $timeout, $anchorScroll) {
		var vm = this;
		vm.save = save;
		vm.saving = false;
		vm.isPublicProfile = (person.account_state === "active");

		function init() {
			vm.product = {emptyCategory:true, warranty: {type: 3},  returns: {type: 1}};
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
			getCategories();
			getDeviser();
			getTags();
			getMetric();
			getSizechart();
			getPaperType();
		}

		init();		

		function getCategories() {
			function onGetCategoriesSuccess(data) {
				vm.allCategories = data.items;
				
			}

			productDataService.getCategories({ scope: 'all' }, onGetCategoriesSuccess, UtilService.onError);
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
			}
			sizechartDataService.getDeviserSizechart({
				scope: 'all',
				deviser_id : person.short_id
			}, onGetSizechartSuccess, UtilService.onError);
		}

		function getLanguages() {
			function onGetLanguagesSuccess(data) {
				vm.languages = data.items;
			}

			languageDataService.getLanguages(onGetLanguagesSuccess, UtilService.onError);
		}

		function getTags() {
			function onGetTagsSuccess(data) {
				vm.tags = angular.copy(data.items);
			}
			tagDataService.getTags(null, onGetTagsSuccess, UtilService.onError);
		}

		function getPaperType() {
			function onGetPaperTypeSuccess(data) {
				vm.papertypes = data.items;
			}

			productDataService.getPaperType(null, onGetPaperTypeSuccess, UtilService.onError);
		}

		function getDeviser() {
			function onGetProfileSuccess(data) {
				vm.deviser = angular.copy(data);
				vm.link_profile = '/deviser/' + data.slug + '/' + data.id + '/store/edit';
				if (vm.deviser.media.profile_cropped)
					vm.profile = currentHost() + vm.deviser.url_images + vm.deviser.media.profile_cropped;
				vm.product.deviser_id = data.id;
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

		function product_published() {
			$window.location.href = currentHost() + vm.link_profile + '?published=true';
		}

		function save(state) {
			vm.errors = false;
			vm.saving = true;
			function onUpdateProductSuccess(data) {
				if (state === 'product_state_draft') {
					saved_draft();					
					vm.saving = false;
				} else if (state === 'product_state_active') {
					product_published();
				}
			}

			function onUpdateProductError(err) {
				vm.saving = false;
				if (err.data.errors && err.data.errors.required && angular.isArray(err.data.errors.required))
					$rootScope.$broadcast(productEvents.requiredErrors, { required: err.data.errors.required })
			}

			function onSaveProductSuccess(data) {
				vm.product.id = angular.copy(data.id);
				if (state === 'product_state_draft') {
					saved_draft();
					vm.saving = false;
				} else if (state === 'product_state_active') {
					product_published();
				}
			}

			function onSaveProductError(err) {
				vm.errors = true;
				vm.saving = false;
				//send errors to components
				if (err.data.errors && err.data.errors.required && angular.isArray(err.data.errors.required))
					$rootScope.$broadcast(productEvents.requiredErrors, { required: err.data.errors.required })
			}
			var required = [];			
			//set state of the product
			vm.product.product_state = angular.copy(state);

			//parse empty multilanguage fields
			UtilService.parseMultiLanguageEmptyFields(vm.product.name);
			UtilService.parseMultiLanguageEmptyFields(vm.product.description);

			//parse faq
			if (angular.isArray(vm.product.faq) && vm.product.faq.length > 0) {
				vm.product.faq.forEach(function(element) {
					UtilService.parseMultiLanguageEmptyFields(element.question);
					UtilService.parseMultiLanguageEmptyFields(element.answer)
				});
			}

			if (vm.product.product_state !== 'product_state_draft') {
				required = productService.validate(vm.product, vm.tags);
				if (vm.product.emptyCategory) {
					required.push("emptyCategory");
				}
			} else {
				//check for null categories
				while (vm.product.categories.indexOf(null) > -1) {
					var pos = vm.product.categories.indexOf(null);
					vm.product.categories.splice(pos, 1);
				}
			}

			if (required.length === 0) {
				vm.product.warranty.value=parseInt(vm.product.warranty.value);
				vm.product.returns.value=parseInt(vm.product.returns.value);
				if (vm.product.id) {
					productDataService.updateProductPriv(vm.product, {
						idProduct: vm.product.id
					}, onUpdateProductSuccess, onUpdateProductError);
				} else {
					productDataService.postProductPriv(vm.product, null, onSaveProductSuccess, onSaveProductError);
				}
			} else {
				vm.saving = false;
				vm.errors = true;
				$rootScope.$broadcast(productEvents.requiredErrors, { required: required });
				$anchorScroll(required[0]);
			}

		}

	}

	angular
		.module('product')
		.controller('createProductCtrl', controller);

}());