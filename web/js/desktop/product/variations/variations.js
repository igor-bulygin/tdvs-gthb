(function () {
	"use strict";

	function controller($scope, productEvents) {
		var vm = this;

		function init(){
			//functions
			vm.setMadeToOrder = setMadeToOrder;
			vm.setPreorder = setPreorder;
			vm.setPreorderEnd = setPreorderEnd;
			vm.setPreorderShip = setPreorderShip;
			//vars
			vm.tag_order = ['Color', 'Material', 'Size', 'Style'];
			vm.tagComparator = tagComparator;
			vm.tags_for_work = [];
			vm.preorder_selected = false;
			vm.made_to_order_selected = false;
			vm.bespoke_selected = false;
			vm.bespoke_language = 'en-US';

		}

		init();

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

		function getTagsByCategory(idCategory) {
			//we look in each tag
			vm.tags.forEach(function(element) {
				//if category is set in tag
				if(element.categories.indexOf(idCategory) > -1) {
					vm.tags_for_work.push(element);
				}
			});
		}

		//watchs
		//watch product

		//events
		////TO DO: set bespoke text required if it is empty in english

		$scope.$on(productEvents.setTagsFromCategory, function(event, args) {
			getTagsByCategory(args.idCategory);
			//get sizecharts value
			//get prints value
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
			languages: '=',
			tags: '='
		}
	}

	angular
		.module('todevise')
		.component('productVariations', component);
}());