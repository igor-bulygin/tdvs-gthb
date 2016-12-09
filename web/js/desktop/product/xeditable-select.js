(function () {
	"use strict";

	function directive() {
		return {
			restrict: 'A',
			link: link
		}

		function link(scope, element, attr) {
			element.find('.editable').on('click', function() {
				element.find('.editable-input').select();
			})
		}
	}


	angular
		.module('todevise')
		.directive('xeditableSelect', directive);
}());