(function () {
	"use strict";

	function controller(UtilService, cartEvents, $scope, cartService, $window) {
		var vm = this;
		vm.isObject = UtilService.isObject;
		
		function getTotalItems(cart) {
			var total = 0;
			vm.cart.packs.forEach(function(pack) {
				total += pack.products.length;
			})
			return total;
		}

		$scope.$on(cartEvents.cartUpdated, function(event, args) {
			vm.cart = angular.copy(args.cart);
			vm.totalItems = getTotalItems(vm.cart);
			cartService.parseTags(vm.cart);
			cartService.setTotalItems(vm.cart);
		});

	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/cart/summary/summary.html',
		controller: controller,
		controllerAs: 'summaryCtrl',
		bindings: {
			state: '=?',
			cart: '<',
		}
	}

	angular
		.module('cart')
		.component('cartSummary', component);

}());