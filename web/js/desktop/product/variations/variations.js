(function () {
	"use strict";

	function controller($scope, productEvents) {
		var vm = this;

		function init(){
			//functions
			vm.setPrintsSelected = setPrintsSelected;
			vm.setMadeToOrder = setMadeToOrder;
			vm.setPreorder = setPreorder;
			vm.setPreorderEnd = setPreorderEnd;
			vm.setPreorderShip = setPreorderShip;
			vm.limitTagOption = limitTagOption;
			vm.deleteOption = deleteOption;
			//deviserSizecharts();
			//vars
			vm.tag_order = ['Color', 'Material', 'Size', 'Style'];
			vm.tagComparator = tagComparator;
			vm.tags_for_work = [];
			vm.show_prints = false;
			vm.prints_selected = false;
			vm.preorder_selected = false;
			vm.made_to_order_selected = false;
			vm.bespoke_selected = false;
			vm.bespoke_language = 'en-US';
			vm.deviserSizecharts = [];

		}

		init();

		function setPrintsSelected(value) {
			vm.show_prints = value;
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
		function deviserSizecharts() {
			console.log(vm);
			console.log(vm.sizecharts);
			vm.sizecharts.forEach(function(element) {
					if(element.type == 1)
						vm.deviserSizecharts.push(element)	
				})
				
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
			sizecharts: '<'
		}
	}

	angular
		.module('todevise')
		.component('productVariations', component);
}());