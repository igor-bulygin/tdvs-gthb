(function () {
	"use strict";

	function directive() {

		function link(scope, elem, attrs, ctrl) {
			elem.bind("keydown keypress", function (e) {
				if(e.which === 13){
					scope.$apply(function() {
						scope.$eval(attrs.onPressEnter);
					});

					e.preventDefault();
				}
			});
		}

		return {
			restrict: 'A',
			link: link
		}
	}

	angular
		.module('util')
		.directive('onPressEnter', directive);

}());