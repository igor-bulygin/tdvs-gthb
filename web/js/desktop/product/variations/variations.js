(function () {
	"use strict";

	function controller($scope, productEvents, productDataService, productService, UtilService) {
		var vm = this;
		//functions
		vm.setPrintsSelected = setPrintsSelected;
		vm.setMadeToOrder = setMadeToOrder;
		vm.setPreorder = setPreorder;
		vm.setPreorderEnd = setPreorderEnd;
		vm.setPreorderShip = setPreorderShip;
		vm.setBespoke = setBespoke;
		vm.deleteSize = deleteSize;
		vm.addSize = addSize;
		vm.addType = addType;
		vm.countriesSelect = countriesSelect;
		vm.sizesSelect = sizesSelect;
		vm.limitTagOption = limitTagOption;
		vm.deleteOption = deleteOption;
		vm.addSizeToSizechart = addSizeToSizechart;
		vm.deleteSizeFromSizechart = deleteSizeFromSizechart;
		vm.sizechartValuesValidation = sizechartValuesValidation;
		vm.optionValidation = optionValidation;
		vm.textFieldValidation = textFieldValidation;

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
		}

		init();

		function setPrintsSelected(value) {
			vm.show_prints = value;
			if(value) {
				if(!vm.product.prints) {
					vm.product.prints={
						type: [[]],
						sizes: [{
							width: 0,
							length: 0
						}]
					}
				} else {
					vm.product.prints = angular.copy(vm.prints_copy);
				}
			} else {
				vm.prints_copy = angular.copy(vm.product.prints);
				delete vm.product.prints;
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

		function setBespoke(value) {
			vm.product['bespoke']['type'] = value ? 1 : 0;
		}

		function addSize() {
			vm.product.prints.sizes.push({width:0,length:0});
		}

		function addType() {
			vm.product.prints.type.push([]);
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

		//creates both a new empty sizechart and a new helper empty sizechart
		function sizesSelect(sizechart, country) {
			vm.product.sizechart = {
				country: country,
				columns: [],
				values: [],
				short_id: sizechart.id
			};
			vm.product.sizechart.columns = angular.copy(sizechart.columns);
			vm.sizechart_empty = angular.copy(vm.product.sizechart);
			vm.sizechart_available_values = [];
			var pos = sizechart.countries.indexOf(country);
			if(pos > -1){
				for(var i = 0; i < sizechart.values.length; i++) {
					vm.sizechart_empty.values.push([sizechart.values[i][pos]]);
					vm.sizechart_available_values[i] = true;
					for(var j = sizechart.countries.length; j < sizechart.values[i].length; j++) {
						vm.sizechart_empty.values[i].push(sizechart.values[i][j]);
					}
				}
			}
		}

		function addSizeToSizechart(pos) {
			vm.product.sizechart.values.push(vm.sizechart_empty.values[pos]);
			vm.sizechart_available_values[pos] = false;
			vm.size_to_add=null;
		}

		function deleteSizeFromSizechart(pos) {
			for(var i = 0; i < vm.sizechart_empty.values.length; i++) {
				if(vm.sizechart_empty.values[i][0] == vm.product.sizechart.values[pos][0])
					vm.sizechart_available_values[i] = true;
			}
			vm.product.sizechart.values.splice(pos, 1);
		}

		function sizechartValuesValidation(value) {
			return UtilService.isZeroOrLess(value) && vm.form_submitted;
		}

		function optionValidation(option) {
			return option.length <= 0 && vm.form_submitted;
		}

		function textFieldValidation(textField, requiredOption) {
			return requiredOption && (!angular.isObject(textField) || !textField['en-US'] || textField['en-US'] == '' || textField['en-US'] == undefined);
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
				var values = productService.searchPrintSizechartsOnCategory(vm.categories, element);
				if(values[0])
					vm.prints = true;
				if(values[1]) {
					vm.show_sizecharts = true;
					if(vm.product.sizechart)
						delete vm.product.sizechart;
				}
			});
		});

		$scope.$on(productEvents.requiredErrors, function (event, args) {
			//vm.forms_submitted is true if we sent the form and we have to apply validations
			vm.form_submitted = true;
			if(args.required.indexOf('madetoorder') > -1) {
				vm.required_madetoorder = true;
			}
			if(args.required.indexOf('preorder') > -1) {
				vm.required_preorder = true; 
			}
			if(args.required.indexOf('bespoke') > -1) {
				vm.required_bespoke = true;
			}
		});

		//tag orders
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
			deviser: '<',
			papertypes: '<'
		}
	}

	angular
		.module('todevise')
		.component('productVariations', component);
}());