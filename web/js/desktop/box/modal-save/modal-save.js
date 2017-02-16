(function () {
	"use strict";

	function controller(productDataService, boxDataService) {
		var vm = this;

		init();

		function init(){
			//get product info
			console.log('boxes modal with ', vm.resolve.productId);
		}
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/box/modal-save/modal-save.html',
		controller: controller,
		controllerAs: 'modalSaveBoxCtrl',
		bindings: {
			resolve: '<',
			dismiss: '&',
			close: '&'
		}
	}

	angular
		.module('box')
		.component('modalSaveBox', component);

}());