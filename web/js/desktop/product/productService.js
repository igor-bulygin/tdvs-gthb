(function () {
	"use strict";

	function service(UtilService) {
		this.searchPrintSizechartsOnCategory = searchPrintSizechartsOnCategory;
		this.validate = validate;
		this.parseProductFromService = parseProductFromService;
		this.tagChangesStockAndPrice = tagChangesStockAndPrice;
		
		function searchPrintSizechartsOnCategory(categories, id) {
			for(var i=0; i < categories.length; i++) {
				if(categories[i].id === id) {
					return [categories[i]['prints'], categories[i]['sizecharts']];
				}
			}
			return false;
		}

		function validate(product) {
			var required = [];
			var main_photo = false;
			if(angular.isArray(product.media.photos) && product.media.photos.length > 0) {
				product.media.photos.forEach(function(element) {
					if(element.main_product_photo)
						main_photo = true;
				});
			}
			//name
			if(!angular.isObject(product.name) || product.name['en-US'] === undefined || product.name['en-US'] === "") {
				required.push('name');
			}
			//categories
			if(angular.isArray(product.categories) && product.categories.length === 0) {
				required.push('categories');
			} else if(product.categories.indexOf(null) > -1) {
				required.push('categories');
			}
			//photos
			if(angular.isArray(product.media.photos) && product.media.photos.length === 0) {
				required.push('photos');
			}
			if(angular.isArray(product.media.photos) && product.media.photos.length > 0 && !main_photo) {
				required.push('main_photo');
			}

			//description
			if(!product.description || !product.description['en-US']) {
				required.push('description');
			}

			//faqs
			if(angular.isArray(product.faq) && product.faq.length > 0) {
				product.faq.forEach(function(element) {
					if(!element.question['en-US'] || 
						element.question['en-US'] === "" || 
						!element.answer['en-US'] ||
						element.answer['en-US'] === "") {
							required.push('faq');
					}
				});
			}

			//manufacturing options
			//madetoorder
			if(angular.isObject(product.madetoorder) && product.madetoorder.type == 1) {
				if(product.madetoorder.value == null || product.madetoorder.value == undefined || typeof(product.madetoorder.value) !== "number" || product.madetoorder.value <= 0) {
					required.push('madetoorder');
				}
			}

			//preorder
			if(angular.isObject(product.preorder) && product.preorder.type==1) {
				if(!product.preorder.ship || !product.preorder.end) {
					required.push('preorder')
				}
			}

			//bespoke
			if(angular.isObject(product.bespoke) && product.bespoke.type == 1) {
				if(!product.bespoke.value || !product.bespoke.value['en-US'] || product.bespoke.value['en-US'] == "") {
					required.push('bespoke');
				}
			}

			//sizecharts
			if(angular.isObject(product.sizechart) && !UtilService.isEmpty(product.sizechart) && !UtilService.isEmpty(product.sizechart.values)) {
				if(!product.sizechart.metric_unit)
					required.push('metric_unit');
				product.sizechart.values.forEach(function(element) {
					if(element.indexOf(0) > -1) {
						required.push('sizechart_values');
					}
				})
			}

			//weight_unit
			if(!product.weight_unit) {
				required.push('weight_unit');
			}
			//dimension_unit
			if(!product.dimension_unit) {
				required.push('dimension_unit');
			}
			//price_stock and all price_stock values
			if(!angular.isArray(product.price_stock) || product.price_stock.length === 0) {
				required.push('price_stock');
			} else {
				product.price_stock.forEach(function (element) {
					//if availability
					if (element.available &&
						(UtilService.isZeroOrLess(element.weight) ||
						UtilService.isZeroOrLess(element.width) ||
						UtilService.isZeroOrLess(element.length) ||
						UtilService.isZeroOrLess(element.price))) {
						required.push('price_stock');
					}
				});
			}
			//warranty
			if(!angular.isObject(product.warranty) || !product.warranty.value || !product.warranty.type) {
				required.push('warranty');
			}
			else if (isNaN(parseInt(product.warranty.value))) {
				required.push('warrantyNotNumber');
			}
			//returns
			if(!angular.isObject(product.returns) || !product.returns.value || !product.returns.type) {
				required.push('returns');
			}
			else if (isNaN(parseInt(product.returns.value))) {
				required.push('returnsNotNumber');
			}
			return required;
		}

		function parseProductFromService(product) {
			var options_to_convert = ['name', 'description', 'slug', 'sizechart', 'preorder', 'returns', 'warranty', 'tags'];
			for(var i = 0; i < options_to_convert.length; i++) {
				product[options_to_convert[i]] = UtilService.emptyArrayToObject(product[options_to_convert[i]]);
			}
			return product;
		}

		function tagChangesStockAndPrice(tags, key) {
			for(i = 0; i < tags.length; i++) {
				if(tags[i].id === key) {
					return tags[i].stock_and_price ? true : false;
				}
			}
		}
	}

	angular.module('product')
		.service('productService', service)

}());