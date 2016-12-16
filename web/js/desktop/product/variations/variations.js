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
					if(element.type === 1 && element.deviser_id === UtilService.returnDeviserIdFromUrl())
						vm.deviserSizecharts.push(element);
				});
		}

		function countriesSelect(sizechart) {
			vm.countriesAvailable = angular.copy(sizechart.countries);
		}

		//creates both a new empty sizechart and a new empty helper sizechart
		function sizesSelect(sizechart, country) {

			//create new sizechart
			vm.product.sizechart = {
				country: country,
				columns: [],
				values: [],
				short_id: sizechart.id
			};
			vm.product.sizechart.columns = angular.copy(sizechart.columns);

			vm.sizechart_empty = angular.copy(vm.product.sizechart);
			vm.sizechart_available_values = [];
			var pos, length;
			if(angular.isArray(sizechart.countries) && sizechart.countries.length > 0) {
				pos = sizechart.countries.indexOf(country);
				length = sizechart.countries.length;
			}
			else {
				pos = 0;
				length = 1;
			}
			if(pos > -1){
				for(var i = 0; i < sizechart.values.length; i++) {
					vm.sizechart_empty.values.push([sizechart.values[i][pos]]);
					vm.sizechart_available_values[i] = true;
					for(var j = length; j < sizechart.values[i].length; j++) {
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

		//watches
		$scope.$watch('productVariationsCtrl.product.bespoke', function(newValue, oldValue) {
			if(angular.isObject(oldValue) && oldValue.type === 0 && angular.isObject(newValue) && newValue.type === 1) {
				vm.bespoke_selected = true;
			}
		}, true);

		$scope.$watch('productVariationsCtrl.product.preorder', function(newValue, oldValue) {
			if(angular.isObject(oldValue) && oldValue.type === 0 && angular.isObject(newValue) && newValue.type === 1) {
				vm.preorder_selected = true;
			}
		}, true);

		$scope.$watch('productVariationsCtrl.product.madetoorder', function(newValue, oldValue) {
			if(angular.isObject(oldValue) && oldValue.type === 0 && angular.isObject(newValue) && newValue.type === 1) {
				vm.made_to_order_selected = true;
			}
		}, true);

		$scope.$watch('productVariationsCtrl.product.prints', function(newValue, oldValue) {
			if(!oldValue && angular.isObject(newValue)) {
				vm.prints_selected = true;
			}
		}, true)
		//watch product

		//events
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
				if(values[0]) {
					vm.prints = true;
					if(angular.isObject(vm.product.sizechart)) {
						delete vm.product.sizechart;
					}
				}
				if(values[1]) {
					if(angular.isObject(vm.product.prints)) {
						delete vm.product.prints;
					}
					vm.show_sizecharts = true;
					if(vm.product.sizechart && !vm.product.from_edit)
						delete vm.product.sizechart;
					else if(vm.product.sizechart && vm.product.from_edit) {
						delete vm.product.from_edit;
						var original_sizechart = angular.copy(vm.product.sizechart);
						for(var i = 0; i < vm.sizecharts.length; i++) {
							if(vm.product.sizechart.short_id === vm.sizecharts[i].id) {
								vm.selected_sizechart = vm.sizecharts[i];
							}
						}
						countriesSelect(vm.selected_sizechart);
						vm.selected_sizechart_country = vm.product.sizechart.country;
						sizesSelect(vm.selected_sizechart, vm.product.sizechart.country)
						vm.product.sizechart.values = angular.copy(original_sizechart.values)
						if(original_sizechart.metric_unit)
							vm.produt.sizechart['metric_unit'] = angular.copy(original_sizechart.metric_unit);
						for(var i = 0; i < vm.sizechart_empty.values.length; i++) {
							vm.product.sizechart.values.forEach(function (element) {
								if(element[0] == vm.sizechart_empty.values[i][0])
									vm.sizechart_available_values[i] = false;
							})
						}
					}
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