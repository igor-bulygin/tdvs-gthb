(function () {
	"use strict";

	function service() {
		this.searchPrintSizechartsOnCategory = searchPrintSizechartsOnCategory;
		
		function searchPrintSizechartsOnCategory(categories, id) {
			var prints = false;
			var sizecharts = false;
			for(var i=0; i < categories.length; i++) {
				if(categories[i].id === id) {
					return [categories[i]['prints'], categories[i]['sizecharts']];
				}
			}
			return false
		}
	}

	angular.module('todevise')
		.service('productService', service)

}());