/*
 * This manages the contact form
 */

(function () {
	"use strict";

	function contactCtrl() {
		var vm = this;

		function init() {
			vm.faqs = _faqs;
		}

		init();

		vm.debugit = function (key) {
			console.log(key);
		}

		vm.changed = function () {
			console.log(vm.selected);
			if (vm.selected == 'a') {
				vm.orderShow = true;
			}
		}

	}

	angular.module('todevise')
		.controller('contactCtrl', contactCtrl);

}());