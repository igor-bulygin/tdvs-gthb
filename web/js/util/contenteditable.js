(function () {
	"use strict";

	function directive() {
		function link(scope, element, attrs, ngModel) {

			function read() {
				var text = element.html();
				text = text.replace(/&nbsp;/g, ' ');
				ngModel.$setViewValue(text);
			}

			ngModel.$render = function () {
				element.html(ngModel.$viewValue || "");
			};

			element.bind('blur keyup change', function () {
				scope.$apply(read);
			});
		}

		return {
			restrict: "A",
			require: "ngModel",
			link: link
		}
	}

	angular
		.module('util')
		.directive('contenteditable', directive);

}());