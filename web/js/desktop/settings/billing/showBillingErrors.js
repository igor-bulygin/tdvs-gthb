(function () {
	"use strict";

	var component = {
		template: "<div ng-if='$ctrl.errors[$ctrl.field]'><span class='purple-text' ng-repeat='error in $ctrl.errors[$ctrl.field]'>{{error}}</span></div>",
		bindings: {
			errors: '<',
			field: '@'
		}
	}

	angular
		.module('todevise')
		.component('showBillingErrors', component);

}());