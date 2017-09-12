(function () {
	"use strict";

	function service(UtilService) {
		this.parseTags = parseTags;
		this.setTotalItems = setTotalItems;
		this.setTotalAmount = setTotalAmount;
		this.setProductsAmount = setProductsAmount;

		function parseTags(cart, tags){
			cart.packs.forEach(function(pack) {
				pack.products.forEach(function(product) {
					product.tags = [];
					for(var key in product.options) {
						for(var i = 0; i < tags.items.length; i++) {
							var obj = {
								values: []
							}
							if(key === 'size') {
								obj.name = 'Size';
								obj.stock_and_price = true;
								if(UtilService.isObject(product.options[key])) {
									if(product.options[key].width && product.options[key].length && product.options[key].metric_unit) {
										obj.values.push(product.options[key].width + 'x' + product.options[key].length + product.options[key].metric_unit);
									}
								} else {
									obj.values.push(product.options[key]);
								}
								product.tags.push(obj);
								break;
							}
							else if(key === tags.items[i].id) {
								obj.name = tags.items[i].name;
								if(tags.items[i].stock_and_price)
									obj.stock_and_price = tags.items[i].stock_and_price;
								if(tags.items[i].name==='Size') {
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

		function setTotalItems(cart) {
			var total = 0;
			if(angular.isArray(cart.packs) && cart.packs.length > 0) {
				cart.packs.forEach(function(pack) {
					if(angular.isArray(pack.products) && pack.products.length > 0) {
						pack.products.forEach(function (product) {
							total += product.quantity;
						})
						
					}
				})
			}
			cart.totalItems = total;
		}

		function setTotalAmount(cart) {
			var total = 0;
			cart.packs.forEach(function(pack) {
				total += pack.pack_price + pack.shipping_price;
			})
			cart.subtotal = total;
		}

		function setProductsAmount(cart) {
			var total = 0;
			cart.packs.forEach(function(pack) {
				total += pack.pack_price;
			});
			cart.subtotal = total;
		}
	}

	angular.module('cart')
		.service('cartService', service);
}());