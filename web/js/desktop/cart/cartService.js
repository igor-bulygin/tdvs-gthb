(function () {
	"use strict";

	function service(tagDataService) {
		this.parseDevisersFromProducts = parseDevisersFromProducts;
		this.parseTags = parseTags;

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

		function parseTags(cart){
			tagDataService.Tags.get()
				.$promise.then(function(dataTags) {
					cart.products.forEach(function(product) {
						product.tags = [];
						for(var key in product.options) {
							for(var i = 0; i < dataTags.items.length; i++) {
								var obj = {
									values: []
								}
								if(key === 'size') {
									obj.name = 'Size'
									obj.values.push(product.options[key]);
									product.tags.push(obj);
									break;
								}
								else if(key === dataTags.items[i].id) {
									obj.name = dataTags.items[i].name;
									if(dataTags.items[i].name==='Size') {
										for(var j = 0; j < product.options[key].length; j++){
											var str = product.options[key][j]['value'] + ' ' + product.options[key][j]['metric_unit'];
											obj.values.push(str);
										}
									} else {
										for(var j = 0; j < product.options[key].length; j++) {
											obj.values.push(product.options[key][j]);
										}
										
									}
									product.tags.push(obj);
									break;
								}
							}
						}
					})

				})
		}
	}

	angular.module('todevise')
		.service('cartService', service);
}());