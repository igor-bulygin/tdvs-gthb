(function () {
	"use strict";

	function controller($scope, productEvents, productService, UtilService, sizechartDataService, metricDataService,$uibModal) {
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
		vm.saveDeviserSizechart=saveDeviserSizechart;
		vm.showNewSizechartForm = showNewSizechartForm;
		vm.new_column=new_column;
		vm.delete_column=delete_column;
		vm.move_column=move_column;
		vm.new_row=new_row;
		vm.removeRow=removeRow;		
		vm.addTableValues=addTableValues;

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
		vm.sizecharts=[];
		vm.countriesAvailable = [];
		vm.finalColumns = [];
		vm.finalCountry;
		vm.showNewSizechart=false;
		vm.invalidNewSizechart=false;
		vm.selected_language=_lang;
		vm.name_language=vm.selected_language;
		vm.mandatory_langs=Object.keys(_langs_required);

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
			vm.savingSizechart=true;
			function onGetSizechartSuccess(data) {vm.sizecharts = data.items;
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
				if(vm.product.sizechart && vm.fromedit) {
					setSizechartFromProduct();
				}
				if (!vm.fromedit && !angular.isUndefined(vm.selected_sizechart) && !angular.isUndefined(vm.selected_sizechart.id) && vm.sizechart_helper_id.indexOf(vm.selected_sizechart.id) === -1) {
					vm.selected_sizechart=vm.sizechart_helper[0];
					vm.product.sizechart=null;
				}
				vm.savingSizechart=false;
				if (vm.sizechart_helper.length>0) {
					vm.show_sizecharts = true;
				}
			}
			sizechartDataService.getSizechart({
				scope: 'all'
			}, onGetSizechartSuccess, UtilService.onError);
		}

		function deviserSizecharts() {
			vm.sizecharts.forEach(function(element) {
				if(element.type === 1 && element.deviser_id === person.short_id)
					vm.deviserSizecharts.push(element);
			});
		}

		// create new sizechart begins

		function showNewSizechartForm() {
			vm.newSizechart= {name:{},categories:[], countries:[], columns:[], values:[], type:1, deviser_id:person.short_id};
			vm.showNewSizechart=true;
			function onGetCountriesSuccess(data) {
				vm.newSizechartAvailableCountries=data.items;
			}
			sizechartDataService.getCountries({}, onGetCountriesSuccess, UtilService.onError);
		}

		function saveDeviserSizechart() {
			vm.invalidNewSizechart=false;
			vm.invalidSizechartCountries=false;
			vm.invalidSizechartValues=false;
			vm.invalidSizechartColumns=false;
			vm.invalidSizechartName=false;

			if (vm.newSizechart.countries.length<1) {
				vm.invalidNewSizechart=true;
				vm.invalidSizechartCountries=true;
			}
			if (vm.newSizechart.columns.length<1) {
				vm.invalidNewSizechart=true;
				vm.invalidSizechartColumns=true;
			}
			if (vm.newSizechart.values.length<1) {
				vm.invalidNewSizechart=true;
				vm.invalidSizechartValues=true;
			}
			angular.forEach(vm.mandatory_langs, function (lang) {
				if (angular.isUndefined(vm.newSizechart.name[lang]) || vm.newSizechart.name[lang].length<1) {
					vm.invalidNewSizechart=true;
					vm.invalidSizechartName=true;
				}
			});
			if (!vm.invalidNewSizechart) {
				vm.savingSizechart=true;
				angular.forEach(vm.newSizechart.values, function (row) {
				for (var i = 0, len = row.length; i < len; i++) {
					row[i]=validateValue(row[i]);
				}
			});
				angular.forEach(vm.selected_categories, function (category) {
					vm.newSizechart.categories.push(category);
				});				
				function onSaveSizechartSuccess(data) {
					vm.sizechart_helper.push(data);
					vm.showNewSizechart=false;
					vm.savingSizechart=false;
				}
				sizechartDataService.postDeviserSizechart(vm.newSizechart, onSaveSizechartSuccess, UtilService.onError);
			}
		}

		function validateValue(value) {
			if (value !=null) {
				 if (value != " " && value.length>0) {
					return value;
				}
			}
			return 0;
		}

		function new_column(column) {
			vm.invalidColumnName=false;
			if (angular.isUndefined(column)) {
				vm.invalidColumnName=true;
				return;
			}
			angular.forEach(vm.mandatory_langs, function (lang) {
				if (angular.isUndefined(column[lang]) || column[lang].length<1) {
					vm.invalidColumnName=true;
				}
			});
			if (!vm.invalidColumnName) {
				vm.newSizechart.columns.push(column);
				angular.forEach(vm.newSizechart.values, function (row) {
					row.push(0);
				});
				vm.addingColumn=false;
				addTableValues();
				vm.invalidSizechartColumns=false;
				vm.newColumn={};
			}
		}

		function delete_column(index) {
			vm.newSizechart.columns.splice(index, 1);
			angular.forEach(vm.newSizechart.values, function (row) {
				row.splice(index, 1);
			});
		}

		function move_column(from, to) {
			vm.newSizechart.columns.splice(index, 1);
			angular.forEach(vm.newSizechart.values, function (row) {
				row.splice(index, 1);
			});
		}

		function new_row() {
			var _len = vm.table_header.length;
			var _data = Array.apply(null, Array(_len)).map(Number.prototype.valueOf, 0);
			vm.newSizechart.values.push(_data);
			vm.invalidSizechartValues=false;
		}

		function removeRow(index) {
			vm.newSizechart.values.splice(index, 1);
		}


		function addTableValues() {
			vm.table_header = [];
			angular.forEach(vm.newSizechart.countries, function (country) {
				vm.table_header.push(country);
			});
			angular.forEach(vm.newSizechart.columns, function (column) {
				vm.table_header.push(column[_lang]);
			});
		}

		// create new sizechart ends

		function countriesSelect(sizechart) {
			vm.countriesAvailable = angular.copy(sizechart.countries);
			vm.selected_sizechart_country=null;
			//sizesSelect(vm.selected_sizechart, vm.countriesAvailable[0]);
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
			if (!angular.isUndefined(sizechart) && !angular.isUndefined(sizechart.values)) {
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
		}

		
		function addSizeToSizechart(pos) {
			if (pos!=null) {
				vm.product.sizechart.values.push(vm.sizechart_empty.values[pos]);
				vm.sizechart_available_values[pos] = false;
				vm.size_to_add=null;
			}
		}

		function deleteSizeFromSizechart(pos) {
			if(angular.isArray(vm.product.sizechart.values[pos]) && vm.product.sizechart.values.length > 0) {
				if (!angular.isUndefined(vm.sizechart_empty)) {
					for(var i = 0; i < vm.sizechart_empty.values.length; i++) {
						if(vm.sizechart_empty.values[i][0] == vm.product.sizechart.values[pos][0])
							vm.sizechart_available_values[i] = true;
					}
				}
			}
			vm.product.sizechart.values.splice(pos, 1);
		}

		function sizechartValuesValidation(value) {
			return UtilService.isZeroOrLess(value) && vm.form_submitted;
		}

		function optionValidation(option, required) {
			return option.length <= 0 && vm.form_submitted && required;
		}

		function textFieldValidation(textField, requiredOption) {
			return requiredOption && (!angular.isObject(textField) || !textField['en-US'] || textField['en-US'] == '' || textField['en-US'] == undefined);
		}

		function setSizechartFromProduct() {
			var original_sizechart = angular.copy(vm.product.sizechart);
			if (angular.isUndefined(vm.selected_sizechart) || vm.selected_sizechart==null) {
				vm.selected_sizechart={};
			}
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
				vm.product.sizechart['metric_unit'] = angular.copy(original_sizechart.metric_unit);
			for(var i = 0; i < vm.sizechart_empty.values.length; i++) {
				vm.product.sizechart.values.forEach(function (element) {
					if(angular.isArray(element) && element.length > 0 && element[0] == vm.sizechart_empty.values[i][0])
						vm.sizechart_available_values[i] = false;
				})
			}
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

		//events
		$scope.$on(productEvents.setVariations, function(event, args) {
			//get tags
			if (!args.isFirstSelection) {
				vm.product.options = {};
			}
			vm.newSizechartForm.$setUntouched();
			vm.newSizechartForm.$setPristine();
			vm.selected_categories=args.categories;
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
					if(vm.product.sizechart && !vm.fromedit)
						delete vm.product.sizechart;
					else if(vm.product.sizechart && vm.fromedit) {
						delete vm.fromedit;
						setSizechartFromProduct();
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
			papertypes: '<',
			fromedit: '<'
		}
	}

	angular
	.module('product')
	.component('productVariations', component);
}());