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
			//Fix for editing when td is focused (tab key navigation on table)
			element.find('.editable').on('focus', function() {
				if (element.children('.editable-click').length>1) { //td with multiple editable elements nested
					var editableChildrens=element.children('.editable-click').not('.editatedElement');
					if (editableChildrens.length>0) {
						var actualChildren=editableChildrens[0]; //take first editable and not edited children
						actualChildren.className += " editatedElement"; //mark for navigated elements
						actualChildren.click();
						if (!element.children('.editable-click').not('.editatedElement').length>0) {
							element.find('a').removeClass("editatedElement"); //clean marks when finish all childrens edition
						}
					}
				}
				else {
					element.find('.editable-click').click();
				}
			});
		}
	}

	angular
		.module('todevise')
		.directive('xeditableSelect', directive);
}());