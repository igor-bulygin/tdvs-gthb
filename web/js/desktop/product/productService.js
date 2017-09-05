(function () {
	"use strict";

	function service(UtilService) {
		this.searchPrintSizechartsOnCategory = searchPrintSizechartsOnCategory;
		this.validate = validate;
		this.parseProductFromService = parseProductFromService;
		this.tagChangesStockAndPrice = tagChangesStockAndPrice;
		this.setOldPriceStockPrices = setOldPriceStockPrices;
		
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
			var options_to_convert = ['name', 'description', 'slug', 'sizechart', 'preorder', 'returns', 'warranty', 'tags', 'options'];
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

		function setOldPriceStockPrices(oldPriceStock, newPriceStock) {
			if(angular.isArray(oldPriceStock) && angular.isArray(newPriceStock) && 
				oldPriceStock.length > 0 && newPriceStock.length > 0) {
				newPriceStock.forEach(function (price_stock_element) {
					//original_artwork_case
					if(price_stock_element.original_artwork) {
						var original_artwork_element = oldPriceStock.find(function(element) {
							return element.original_artwork;
						})
						if(original_artwork_element)
							copyProductProperties(original_artwork_element, price_stock_element);
					}
					//all_other_cases
					oldPriceStock.forEach(function (old_price_stock_element) {
						if(compareTwoPriceStockOptions(old_price_stock_element, price_stock_element))
							copyProductProperties(old_price_stock_element, price_stock_element);
					})
				})
			}
		}

		//returns true if parameters are same elements (or similar enough)
		function compareTwoPriceStockOptions(oldPriceStock, newPriceStock) {
			var old_properties_count = Object.keys(oldPriceStock.options).length;
			var new_properties_count = Object.keys(newPriceStock.options).length;
			if(!UtilService.isEmpty(oldPriceStock.options) && !UtilService.isEmpty(newPriceStock.options)) {
				//iterate in old options keys and look for each key in new options
				for (var key in oldPriceStock.options) {
					var compare_count = 0;
					if(newPriceStock.options.hasOwnProperty(key)) { //if new price options have old property
						if(newPriceStock.options[key].length > 0) { //and it is not empty
							if(oldPriceStock.options[key].length === newPriceStock.options[key].length) { //if option have same length than the old one
								if(!angular.equals(oldPriceStock.options[key], newPriceStock.options[key])) { //if its not equal to old one its not the same element
									return false;
								}
							}
							//if new product has more options than old one then maybe user added one more option. 
							//Example: "color": ["black", "red"] goes to ["black", "red", "white"]
							if(newPriceStock.options[key].length > oldPriceStock.options[key].length) { 
								var count_same_options = 0;
								newPriceStock.options[key].forEach(function (option) {
									var option_exists_previously = oldPriceStock.options[key].find(function(element) {
										return angular.equals(option, element);
									})
									if(option_exists_previously)
										count_same_options++;
								})
								if(count_same_options !== newPriceStock.options[key].length)
									return false;
							}
							//if there is a new option that has less values than the old one, then the options are not the same
							if(newPriceStock.options[key].length < oldPriceStock.options[key].length)
								return false;
						//if options satisfy previous conditions, then we add it to compare_count
						compare_count++;
						}
					}
					else {
						return false;
					}
				}
				if(compare_count >= new_properties_count-1) {
					return true;
				}
				return false;
			}
		}

		function copyProductProperties(oldProduct, newProduct) {
			Object.assign(newProduct, {
				height: oldProduct.height,
				width: oldProduct.width,
				length: oldProduct.length,
				weight: oldProduct.weight,
				price: oldProduct.price,
				stock: oldProduct.stock
			});
		}
	}

	angular.module('product')
		.service('productService', service)

}());