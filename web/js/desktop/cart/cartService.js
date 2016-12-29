(function () {
	"use strict";

	function service() {
		this.parseDevisersFromProducts = parseDevisersFromProducts;

		function parseDevisersFromProducts(cart) {
			var devisers = [];
			cart.products.forEach(function(product) {
				var isDeviserInArray = false;
				for(var i = 0; i < devisers.length; i++) {
					if(devisers[i].deviser_id === product.deviser_id)
						isDeviserInArray = true;
				}
				if(!isDeviserInArray) {
					devisers.push({
						deviser_id: product.deviser_id,
						deviser_name: product.deviser_name
					});
				}
			})
			return devisers;
		}
	}

	angular.module('todevise')
		.service('cartService', service);
}());