(function () {
	"use strict";

	function service(UtilService) {
		this.searchPrintSizechartsOnCategory = searchPrintSizechartsOnCategory;
		this.validate = validate;
		this.parseProductFromService = parseProductFromService;
		this.tagChangesStockAndPrice = tagChangesStockAndPrice;
		this.setOldPriceStockPrices = setOldPriceStockPrices;
		var mandatory_langs=Object.keys(_langs_required);
		
		function searchPrintSizechartsOnCategory(categories, id) {
			for(var i=0; i < categories.length; i++) {
				if(categories[i].id === id) {
					return [categories[i]['prints'], categories[i]['sizecharts']];
				}
			}
			return false;
		}

		function tagIsRequired(tags, key) {
			var tag = tags.find(function(element) {
				return angular.equals(element.id, key);
			})
			if(UtilService.isObject(tag))
				return tag.required;
			else {
				return null;
			}
		}

		function validate(product, tags) {
			var required = [];
			var main_photo = false;
			if(angular.isArray(product.media.photos) && product.media.photos.length > 0) {
				product.media.photos.forEach(function(element) {
					if(element && element.main_product_photo)
						main_photo = true;
				});
			}
			//name
			if(!angular.isObject(product.name)) {
				required.push('name');
			}
			else {
				angular.forEach(mandatory_langs, function (lang) {
					if (angular.isUndefined(product.name[lang]) || product.name[lang].length<1) {
						required.push('name');
					}
				});
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
			if(!product.description) {
				required.push('description');
			}
			else {
				angular.forEach(mandatory_langs, function (lang) {
					if (angular.isUndefined(product.description[lang]) || product.description[lang].length<1) {
						required.push('description');
					}
				});
			}
			//faqs
			if(angular.isArray(product.faq) && product.faq.length > 0) {
				product.faq.forEach(function(element) {
					angular.forEach(mandatory_langs, function (lang) {
						if (angular.isUndefined(element.question[lang]) || element.question[lang].length<1
							|| angular.isUndefined(element.answer[lang]) || element.answer[lang].length<1) {
							required.push('faq');
						}
					});					
				});
			}
			//options
			for(var key in product.options) {
				if(tagIsRequired(tags, key)) {
					if(angular.isArray(product.options[key]) && product.options[key].length === 1 && product.options[key][0].length === 0)
						required.push('options');
				}
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
				if (angular.isUndefined(product.bespoke.value) || product.bespoke.value == null) {
					required.push('bespoke');
				}
				else {
						angular.forEach(mandatory_langs, function (lang) {
						if (angular.isUndefined(product.bespoke.value) || angular.isUndefined(product.bespoke.value[lang]) || product.bespoke.value[lang].length<1) {
							required.push('bespoke');
						}
					});
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
			if(!angular.isObject(product.warranty) || angular.isUndefined(product.warranty.type) || product.warranty.type===null || (!product.warranty.value && product.warranty.type !=0) ) {
				required.push('warranty');
			}
			else if (product.warranty.type !=0 && isNaN(parseInt(product.warranty.value))) {
				required.push('warrantyNotNumber');
			}
			//returns
			if(!angular.isObject(product.returns) || angular.isUndefined(product.returns.type) || product.returns.type===null || (!product.returns.value && product.returns.type !=0) ) {
				required.push('returns');
			}
			else if (product.returns.type !=0 && isNaN(parseInt(product.returns.value))) {
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
			var tag = tags.find(function(element) {
				return angular.equals(element.id, key)
			});
			if(tag) {
				return tag.stock_and_price;
			}
			return false;
		}

		function setOldPriceStockPrices(oldPriceStock, newPriceStock, cartesian, old_cartesian) {
			if(angular.isArray(oldPriceStock) && angular.isArray(newPriceStock) && 
				oldPriceStock.length > 0 && newPriceStock.length > 0) {

				newPriceStock.forEach(function (new_price_stock_element) {
					//original_artwork_case
					if(new_price_stock_element.original_artwork) {
						var original_artwork_element = oldPriceStock.find(function(element) {
							return element.original_artwork;
						});
						if(original_artwork_element)
							copyProductProperties(original_artwork_element, new_price_stock_element);
					} else //all_other_cases
					{
						oldPriceStock.forEach(function(old_price_stock_element) {
							var old_properties_count = Object.keys(old_price_stock_element.options).length;
							var new_properties_count = Object.keys(new_price_stock_element.options).length;
							var diff = new_properties_count - old_properties_count;
							var option_obj;
							switch(diff) {
								//less options than before
								case -1:
									//option was in oldPriceStock, but is not present in newPriceStock
									//which option and value was erased?
									option_obj = getDiffOptionAndValue(old_price_stock_element, new_price_stock_element);
									//recreate new_options and compare
									var new_options = Object.assign(angular.copy(new_price_stock_element.options), option_obj);
									if(angular.equals(new_options, old_price_stock_element.options))
										copyProductProperties(old_price_stock_element, new_price_stock_element);
									break;
								//same number of options
								case 0:
									//2 cases:
										//when cartesian has more or less options
										if(cartesian.length != old_cartesian.length) {
										//which value in which option was added or deleted?
											var old_element = findOptionInPriceStock(new_price_stock_element.options, oldPriceStock);
											if(UtilService.isObject(old_element) && !UtilService.isEmpty(old_element)) {
												copyProductProperties(old_element, new_price_stock_element);
											}
										}
										else {
											//when an option was added inside an option i.e.: color: ["red"] -> ["red, blue"]
											//when an option was deleted inside an option i.e.: color: ["red, blue"] -> ["red"]
											//first, find other elements with same options
											var old_element = findOptionInPriceStock(new_price_stock_element.options, oldPriceStock);
											if(UtilService.isObject(old_element) && !UtilService.isEmpty(old_element)) {
												copyProductProperties(old_element, new_price_stock_element);
											}
											//When the element was not found, find what option was modified
											else {
												//get the cartesians difference
												var diff_option = getDiffCartesians(cartesian, old_cartesian);
												var key = diff_option['key'];
												var value;
												if(UtilService.isObject(diff_option) && angular.isArray(diff_option['value']) && diff_option['value'].length > 0)
													value = diff_option['value'][0];
												if(value) {
													//find position of the element added/deleted
													var pos = new_price_stock_element.options[key].indexOf(value);
													var element_to_find = angular.copy(new_price_stock_element);
													//when option was deleted
													if(pos < 0) {
														//add option to new_price_stock_element.options[diff_option[key]]
														element_to_find.options[key].push(value);
													}
													//when option was added
													else {
														//substract option in new_price_stock_element.options[diff_option[key]] knowning that pos has the position
														element_to_find.options[key].splice(pos, 1);
													}
													//find in oldPriceStock such option
													var old_element = findOptionInPriceStock(element_to_find.options, oldPriceStock);
													//copy values
													if(UtilService.isObject(old_element) && !UtilService.isEmpty(old_element)) {
														copyProductProperties(old_element, new_price_stock_element);
													}
												}
											}
										}

									break;
								//more options than before
								case 1:
									//option wasnt in oldPriceStock, but is now in newPriceStock
									//which option and value was added?
									option_obj = getDiffOptionAndValue(new_price_stock_element, old_price_stock_element);
									//recreate old options and compare
									var old_options = Object.assign(angular.copy(old_price_stock_element.options), option_obj);
									if(angular.equals(old_options, new_price_stock_element.options))
										copyProductProperties(old_price_stock_element, new_price_stock_element);
									break;
							} //end switch
						}) //end iteration oldPriceStock
					} //end else
				}) //end newPriceStock iteration
			} //end if
		}

		//compares two options objects and returns diff key and value
		function getDiffOptionAndValue(firstElement, secondElement) {
			var new_object = {};
			for(var key in firstElement.options) {
				if(!secondElement.options[key]) {
					new_object[key] = angular.copy(firstElement.options[key]);
					return new_object;
				}
			}
			return undefined;
		}

		//get which option and value was added or deleted
		function getDiffCartesians(firstCartesian, secondCartesian) {
			var old_cartesian_option, new_cartesian_option;
			firstCartesian.forEach(function(first_element_cartesian) {
				var second_element_cartesian = secondCartesian.find(function(element) {
					return angular.equals(first_element_cartesian, element);
				});
				if(!second_element_cartesian)
					old_cartesian_option = angular.copy(first_element_cartesian);
			})
			secondCartesian.forEach(function(second_element_cartesian) {
				var first_element_cartesian = firstCartesian.find(function(element) {
					return angular.equals(second_element_cartesian, element);
				});
				if(!first_element_cartesian)
					new_cartesian_option = angular.copy(second_element_cartesian);
			})
			var object = {};
			for(var key in old_cartesian_option) {
				if(!angular.equals(old_cartesian_option[key], new_cartesian_option[key])) {
					object = {
						key: key,
						value: UtilService.arrayDiff(old_cartesian_option[key], new_cartesian_option[key])
					}
				}

			}
			return object;
		}

		function findOptionInPriceStock(options, priceStock) {
			//we have to sort the arrays, Kate
			var sorted_options = angular.copy(options);
			for(var key in sorted_options) {
				if(angular.isArray(sorted_options[key]))
					sorted_options[key].sort();
			}
			var priceStockElement = priceStock.find(function (element) {
				var sorted_element = angular.copy(element);
				for(var key in sorted_element.options) {
					if(angular.isArray(sorted_element.options[key]))
						sorted_element.options[key].sort();
				}
				return angular.equals(sorted_element.options, sorted_options);
			});
			return priceStockElement;
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