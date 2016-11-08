(function() {

	"use strict";

	function controller(deviserDataService, productDataService, languageDataService, toastr, UtilService, tagDataService, $scope, $rootScope, productEvents, $location) {
		var vm = this;
		
				
		vm.deviser_id = UtilService.returnDeviserIdFromUrl();
		vm.product_id = UtilService.returnProductIdFromUrl();
		
		function init(){
			getLanguages();
			getCategories();
			getDeviser();
			getProduct();
		}

		init();

		function getLanguages() {
			languageDataService.Languages.get()
				.$promise.then(function(dataLanguages) {
					vm.languages = dataLanguages.items;
				}, function(err) {
					toastr.erro(err);
				});

		}

		function getCategories() {
			productDataService.Categories.get()
				.$promise.then(function (dataCategories) {
					vm.allCategories = dataCategories.items;
				}, function(err) {

				});
		}
		//The getDeviser probably can be deleted.
		function getDeviser(){
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function(dataDeviser) {
				vm.deviser = dataDeviser;
				vm.deviser_original = angular.copy(dataDeviser);
			}, function(err) {

			});

		}

		function getProduct() {
			productDataService.Product.get({
				product_id: UtilService.returnProductIdFromUrl()
			}).$promise.then(function(dataProduct) {
				//vm.product = dataProduct;

				//vm.product_original = angular.copy(dataProduct);
				
			}, function (err) {
				toastr.error(err);
			});
		}
		
		
	//}
	//watches
	$scope.$watch('editProductCtrl.product', function (newValue, oldValue) {
		if(newValue) {
			if(!angular.equals(newValue, vm.product_original)) {
				$rootScope.$broadcast(productEvents.product_changed, {value: true, product: newValue});
			} else {
				$rootScope.$broadcast(productEvents.product_changed, {value: false});
			}
		}
	}, true);

	//events
	$scope.$on(productEvents.product_updated, function(event, args) {
		getProduct();
	});

	$scope.$on(productEvents.product_changed, function(event, args) {
		if(args.product)
			vm.product = angular.copy(args.product);
	});

}

	angular
		.module('todevise')
		.controller('editProductCtrl',controller);

}());