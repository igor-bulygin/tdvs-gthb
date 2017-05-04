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
			});
			//Fix to editing when element is focused (tab key navigation for editable elements in table)
			element.find('.editable').on('focus', function() {
				element.find('.editable-click').click();
			});
		}
	}

	angular
		.module('todevise')
		.directive('xeditableSelect', directive);
}());