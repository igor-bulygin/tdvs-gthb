(function() {

	"use strict";

	function controller(deviserDataService, productDataService, languageDataService, toastr, UtilService, tagDataService, $scope, $rootScope, productEvents, $location) {
		var vm = this;
		
				
		vm.deviser_id = UtilService.returnDeviserIdFromUrl();
		vm.product_id = UtilService.returnProductIdFromUrl();
		vm.categories_helper = [];
				
		
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
			productDataService.Categories.get({scope: 'all',limit: '999'})
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
			productDataService.ProductPriv.get({
				idProduct: UtilService.returnProductIdFromUrl()
			}).$promise.then(function(dataProduct) {
				vm.product = dataProduct;
				vm.product_original = angular.copy(dataProduct);
				
				angular.forEach(dataProduct.categories, function(value, key){
						
					angular.forEach(vm.allCategories, function(values,keys){

						if(value == values.id){
							var catHelp =[]
							catHelp = values.path.split("/");
							catHelp.shift();
							catHelp.pop();
							vm.categories_helper[key]=catHelp;
						}
					});
					
				});
				console.log(vm.categories_helper);


			}, function (err) {
				toastr.error(err);
			});
		}
		
		
	//}
		
}

	angular
		.module('todevise')
		.controller('editProductCtrl',controller);

}());