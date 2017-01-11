(function () {
	"use strict";

	function controller() {
		var vm = this;
		vm.bank_location=['Canada', 'New Zealand', 'USA', 'Rest of the world'];
		vm.bank_information = {
			location: 'Rest of the world'
		}
	}

	angular.module('todevise')
		.controller('billingCtrl', controller);

}());