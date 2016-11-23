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
			});
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

		//Get sizecharts by categories.
		function categoriesSizecharts(categories) {
			vm.sizechart_helper = [];
			vm.sizechart_helper_id = [];
			vm.sizecharts.forEach(function(sizechart) {
				for(var i = 0; i < categories.length; i++) {
					if(sizechart.categories.indexOf(categories[i]) > -1 && vm.sizechart_helper_id.indexOf(sizechart.id) === -1) {
						vm.sizechart_helper_id.push(sizechart.id)
						vm.sizechart_helper.push(sizechart);
					}
				}
			});
		}

		//If you choose a personal Sizechart you need to search how many do you have. You can have none.
		function deviserSizecharts() { 
			vm.sizecharts.forEach(function(element) {
					if(element.type == 1) //&& element.deviser_id == vm.product.deviser_id) || IMPORTANT, the comment is for test only.
						vm.deviserSizecharts.push(element)	
				});
		}

		function countriesSelect(sizechart) {
			vm.countriesAvailable = angular.copy(sizechart.countries);
		}

		//In this function we select all the data for populate the table, we should rename it for give it a better notion of his work
		function sizesSelect(sizechart, country) {
			vm.product.sizechart = {
				country: country,
				columns: [],
				values: []
			};
			vm.product.sizechart.columns = angular.copy(sizechart.columns);
			var pos = sizechart.countries.indexOf(country);
			if(pos > -1){
				for(var i = 0; i < sizechart.values.length; i++) {
					vm.product.sizechart.values.push([sizechart.values[i][pos]]);
					for(var j = sizechart.countries.length; j < sizechart.values[i].length; j++) {
						vm.product.sizechart.values[i].push(sizechart.values[i][j]);
					}
				}
			}
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
			categoriesSizecharts(args.categories);
			deviserSizecharts();
			//get sizecharts and prints value
			vm.show_sizecharts = false;
			vm.prints = false;
			args.categories.forEach(function(element) {
				var values = searchPrintsSizecharts(element);
				if(values[0])
					vm.prints = true;
				if(values[1])
					vm.show_sizecharts = true;
					if(vm.product.sizechart)
						delete vm.product.sizechart;
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