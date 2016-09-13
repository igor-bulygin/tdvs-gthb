(function () {
	"use strict";

	function directive() {

		function link(scope, element, attrs) {
			function compare(v1, v2) {
				return v1 != v2;
			}
			scope.$watch('value1', function () {
				scope.result = compare(scope.value1, scope.value2);
			});
			scope.$watch('value2', function () {
				scope.result = compare(scope.value1, scope.value2);
			});
		}

		return {
			restrict: 'A',
			scope: {
				value1: "@",
				value2: "@",
				result: "="
			},
			link: link
		};
	}

	angular
		.module('util')
		.directive('tdvComparator', directive);

}());