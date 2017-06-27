(function () {
	"use strict";

	function controller(UtilService, cartEvents, $scope, cartService) {
		var vm = this;
		vm.isObject = UtilService.isObject;
		vm.proceedToCheckout = proceedToCheckout;

		function proceedToCheckout() {
			vm.state.state = 2;
		}

		$scope.$on(cartEvents.cartUpdated, function(event, args) {
			vm.cart = angular.copy(args.cart);
			cartService.parseTags(vm.cart);
			vm.devisers = cartService.parseDevisersFromProducts(vm.cart);
		});

	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/cart/summary/summary.html',
		controller: controller,
		controllerAs: 'summaryCtrl',
		bindings: {
			state: '<',
			cart: '<',
			devisers: '<'
		}
	}

	angular
		.module('cart')
		.component('cartSummary', component);

}());