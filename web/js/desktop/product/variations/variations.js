(function () {
	"use strict";

	function controller($scope, productEvents, productDataService) {
		var vm = this;
		//functions
		vm.setPrintsSelected = setPrintsSelected;
		vm.setMadeToOrder = setMadeToOrder;
		vm.setPreorder = setPreorder;
		vm.setPreorderEnd = setPreorderEnd;
		vm.setPreorderShip = setPreorderShip;
		vm.deleteSize = deleteSize;
		vm.addSize = addSize;
		vm.countriesSelect = countriesSelect;
		vm.sizesSelect = sizesSelect;
		vm.limitTagOption = limitTagOption;
		vm.deleteOption = deleteOption;

		//vars
		vm.tag_order = ['Color', 'Material', 'Size', 'Style'];
		vm.tagComparator = tagComparator;
		vm.tags_for_work = [];
		vm.show_prints = false;
		vm.prints_selected = false;
		vm.size_selected = false;
		vm.savedSize_selected = false;
		vm.preorder_selected = false;
		vm.made_to_order_selected = false;
		vm.bespoke_selected = false;
		vm.bespoke_language = 'en-US';
		vm.deviserSizecharts = [];
		vm.countriesAvailable = [];
		vm.sizechart_helper = [];
		vm.finalColumns = [];
		vm.finalCountry;

		function init(){
			getPaperType();
		}

		init();

		function setPrintsSelected(value) {
			vm.show_prints = value;
			vm.product.prints={
				type: [null],
				sizes: [null]
			}
		}

		function setMadeToOrder(value) {
			vm.product['madetoorder']['type'] = value ? 1 : 0;
		}

		function setPreorder(value) {
			vm.product['preorder']['type'] = value ? 1 : 0;
		}

		function setPreorderEnd(newDate, oldDate) {
			vm.product.preorder.type = 1;
			vm.product.preorder['end'] = newDate;
		}

		function setPreorderShip(newDate, oldDate) {
			vm.product.preorder.type = 1;
			vm.product.preorder['ship'] = newDate;
		}

		function getPaperType() {
			productDataService.PaperType.get()
				.$promise.then(function (dataPaperType) {
					vm.paperTypes = dataPaperType.items;
				}, function (err){
					console.log(err);
				});
		}

		function addSize() {
			vm.product.prints.sizes.push(null)
		}

		function deleteSize(index) {
			if(vm.product.prints.sizes.length > index) {
				vm.product.prints.sizes.splice(index, 1);
			}
		}

		function getTagsByCategory(categories) {
			//categories come in array form
			vm.tags_setted = []; //helper
			vm.tags_for_work = [];
			categories.forEach(function(idCategory) {
				//we look in each tag
				vm.tags.forEach(function(element) {
					//if category is set in tag and is not set in tags_setted
					if(element.categories.indexOf(idCategory) > -1 && vm.tags_setted.indexOf(element.id) === -1) {
						vm.tags_setted.push(element.id);
						vm.tags_for_work.push(element);
					}
				});
			});
			vm.tags_setted.forEach(function(element) {
				if(!vm.product.options[element]) {
					vm.product.options[element] = [[]];
				}
			})
		}

		function limitTagOption(limit, array) {
			if(array.length > limit)
				array.splice(1, array.length-1);
		}

		function deleteOption(tag, index) {
			if(index >= 0) {
				vm.product.options[tag].splice(index,1);
			}
		}

		//This function serves to chose the sizecharts of the categories selecteds || CAREFULL. It was not controlled what happens when you chose 2 categories
		// it will be controled because generate duplicates
		// I'm testing with the category /Fashion/Womenswear/b/c but works with others as well
		function categoriesSizecharts() {
			vm.product.categories.forEach(function(cate) {
				vm.sizecharts.forEach(function(element) {
					for (var i = 0; i < element.categories.length; i++) {
						if(element.categories[i]==cate)
							vm.sizechart_helper.push(element);
					}
				});	
			});
		}

		//If you choose a personal Sizechart you need to search how many do you have. You can have none.
		function deviserSizecharts() { 
			vm.sizecharts.forEach(function(element) {
					if(element.type == 1) //&& element.deviser_id == vm.product.deviser_id) || IMPORTANT, the comment is for test only.
						vm.deviserSizecharts.push(element)	
				});
		}
		//

		function countriesSelect(sizechart) {
			vm.countriesAvailable =  sizechart.countries;
		}

		//In this function we select all the data for populate the table, we should rename it for give it a better notion of his work
		function sizesSelect(sizechart, country) {
			vm.pos; // I think this is a very bad solution, having to store the lenght, maybe is other better solution 
			vm.lon;
			vm.finalSizes = [];
			vm.finalTable = [];
			if (typeof country === "undefined"){ 	//If you select a deviserSizechart you dont have countries, you only have one
				vm.finalCountry = sizechart.country //Is not returning the variable 'country' when is a deviserSizechart
				vm.pos=0;
				vm.lon=0;
			}
			else{
				vm.lon = sizechart.countries.length;
				for (var i = 0; i < vm.lon; i++) {
					if(sizechart.countries[i] == country){
						vm.pos=i;
						vm.finalCountry = country;
					}
				}
			}
			sizechart.values.forEach(function(element) {
					vm.finalSizes.push(element[vm.pos])
				});	
			vm.finalColumns = sizechart.columns;
			vm.finalTable=sizechart.values;
			vm.size_selected = true;
		}

		function searchPrintsSizecharts(id) {
			var prints = false;
			var sizecharts = false;
			for(var i = 0; i < vm.categories.length; i++) {
				if(vm.categories[i].id === id) {
					return [vm.categories[i]['prints'], vm.categories[i]['sizecharts']];
				}
			}
			return false;
		}

		//watchs
		//watch product

		//events
		////TO DO: set bespoke text required if it is empty in english
		$scope.$on(productEvents.setVariations, function(event, args) {
			//get tags
			getTagsByCategory(args.categories);
			deviserSizecharts();
			categoriesSizecharts();
			//get sizecharts and prints value
			vm.show_sizecharts = false;
			vm.prints = false;
			args.categories.forEach(function(element) {
				var values = searchPrintsSizecharts(element);
				if(values[0])
					vm.prints = true;
				if(values[1])
					vm.show_sizecharts = true;
			});
		});

		//orders
		function tagComparator(option) {
			if(vm.tag_order.indexOf(option.name) > -1)
				return vm.tag_order.indexOf(option.name)
		}
		
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/product/variations/variations.html',
		controller: controller,
		controllerAs: 'productVariationsCtrl',
		bindings: {
			product: '<',
			languages: '<',
			tags: '<',
			categories: '<',
			metric: '<',
			sizecharts: '<',
			deviser: '<'
		}
	}

	angular
		.module('todevise')
		.component('productVariations', component);
}());