var todevise = angular.module('todevise', ['ui.bootstrap', 'angular-multi-select', 'angular-unit-converter', 'angular-img-dl', 'global-deviser', 'global-desktop', 'global', 'api', "ngFileUpload", "ngImgCrop", 'ui.bootstrap.datetimepicker', 'angular-slugifier']);
var global_deviser = angular.module('global-deviser');

todevise.controller('productCtrl', ["$scope", "$timeout", "$sizechart", "$product", "$category_util", "toastr", "$modal", "Upload", "$http", "$cacheFactory", function($scope, $timeout, $sizechart, $product, $category_util, toastr, $modal, Upload, $http, $cacheFactory) {
	$scope.lang = _lang;
	$scope.deviser = _deviser;
	$scope.product = _product;
	$scope.mus = _mus;
	$scope.countries = _countries;
	$scope.countries_lookup = _countries_lookup;
	$scope.currencies = _currencies;
	$scope.deviser_sizecharts = _deviser_sizecharts;

	$scope.api_sizechart = {};
	$scope.api_available_sizes = {};
	$scope.api_deviser_sizechart = {};
	$scope.api_sizechart_country = {};

	$scope.tmp_selected_size = "";
	$scope.tmp_selected_sizechart_country = "";

	$scope.c_tags = $cacheFactory("tags");
	$scope.c_categories = $cacheFactory("categories");
	$scope.c_sizecharts = $cacheFactory("sizecharts");
	$scope.c_tagsInCategory = $cacheFactory("tagsInCategory");

	$scope._base_product_photo_url = _base_product_photo_url;

	/*
	 * PHP's arrays have the same notation for arrays and objects and (to|from)JSON
	 * get's confused. That's why we must help it here.
	 */
	global.arrayToObject($scope.product, ["name", "slug", "description", "media", "options", "madetoorder", "sizechart", "bespoke", "preorder", "returns", "warranty"]);

	//Required for the name and description dropdowns
	$scope.langs = [];
	angular.forEach(_langs, function(v, k) {
		var sw = $scope.product.name.hasOwnProperty(k);
		$scope.langs.push({ "key": k, "value": v, "checked": sw});
	});

	//Required for the categories dropdown
	$scope.categories = $category_util.create_tree(_categories);

	//Testers
	$scope.dump = function(obj) {
		return angular.toJson(obj, 4);
	};
	//End testers

	/**
	  * Once the categories of a product have changed, we must iterate over
	  * each category and find all the tags it is assigned to, and if those tags
	  * are required.
	  */
	$scope.$watch("product.categories", function(_new, _old) {
		var _tags_in_categories = [];
		var _tmp_tags_ids = []; //We'll use this to avoid duplicate tags

		/*
		 * Check if we should generate "sizechart" table or activate the "prints" mode.
		 */
		var _use_sizecharts = false;
		var _use_prints = false;
		$scope.required_tags_ids = [];
		angular.forEach(_new, function(short_id) {
			//Generate the tags that must be shown
			var __category = $scope.getCategory(short_id);
			var __tags = $scope.getTagsInCategory(short_id);

			angular.forEach(__tags, function(_tag) {
				if(_tmp_tags_ids.indexOf(_tag.short_id) !== -1) return;

				if(_tag.required === true) {
					$scope.required_tags_ids.push(_tag.short_id);
				}

				_tags_in_categories.push(_tag);
				_tmp_tags_ids.push(_tag.short_id);
			});

			//Check if the category contains a size chart
			if(__category.sizecharts === true) _use_sizecharts = true;

			//Check if the category contains prints
			if(__category.prints === true) _use_prints = true

		});
		$scope.use_prints = _use_prints;

		/*
		 * Fetch all sizecharts that belong to _tmp_tags_ids
		 * If the result is an empty array, force "_use_sizecharts" to false.
		 */
		if(_use_sizecharts) {
			$sizechart.get({
				"categories": {
					"$in": _new
				}
			}).then(function(_sizecharts) {
				if(_sizecharts.length === 0) {
					_use_sizecharts = false;
					$scope.sizechart = [];
				} else {
					$scope.sizecharts = _sizecharts;

					$scope.c_sizecharts.removeAll();
					angular.forEach($scope.sizecharts, function(sizechart) {
						$scope.c_sizecharts.put(sizechart.short_id, sizechart);
					});
				}
				$scope.use_sizecharts = _use_sizecharts;
			});
		} else {
			/**
			 * Check if we had something in the sizechart variable. If that is the case, it means that we
			 * previously had selected a category that had sizecharts, but now we have a category that doesn't
			 * have any sizecharts, so we should empty the sizechart variable.
			 */
			if($scope.sizecharts !== undefined && !angular.equals([], $scope.sizecharts)) {
				$scope.sizecharts = [];
			}
			$scope.use_sizecharts = _use_sizecharts;
		}

		$scope.sortTags(_tags_in_categories);
		$scope.tags_from_categories = _tags_in_categories;

		/*
		 * Check what tags (a) we currently have in product.options, what tags (b) we should
		 * have, based on the selected categories, and:
		 * - remove the tags that are in a, but not in b
		 * - create the tags that are in b, but not in product.options
		 */
		var a = Object.keys($scope.product.options);
		var b = [];
		angular.forEach(_tags_in_categories, function(tag) {
			b.push(tag.short_id);
		});

		var difference = _.difference(a, b);
		angular.forEach(difference, function(tag_id) {
			delete $scope.product.options[tag_id];
		});
		angular.forEach(b, function(tag_id) {
			if(!$scope.product.options.hasOwnProperty(tag_id)) {
				$scope.product.options[tag_id] = [];
			}
		});

		console.log("Tags", $scope.tags_from_categories);
	});

















	/**
	 * React to changes in the dropdown containing normal size charts
	 */
	$scope.$watch("tmp_selected_sizechart", function(_new, _old) {
		/**
		 * If both _new and _old are undefined, or _new is an empty array and _old is undefined
		 * it means that angular is still loading, so don't do anything.
		 */
		if(_new === undefined && _old === undefined) return;
		if(angular.isArray(_new) && _new.length === 0 && _old === undefined) return;

		/**
		 * If we deselect a sizechart, make sure to:
		 * * clean the countries dropdown
		 * * empty the sizechart values if and only if the values are pristine.
		 */
		if(_new.length !== 1){
			$scope.sizechart_countries = [];

			/**
			 * If the values are pristine, we're free to clean them, but if they're not clean, it
			 * means that this $watch was triggered by a modification of the values table, and not
			 * by a deselection of the sizecharts dropdown.
			 */
			if($scope.product.sizechart.pristine === true) {
				console.log("CLEARING VALUES 1");
				$scope.product.sizechart.values = [];
			}
			return;
		}

		var _sizechart = _new[0];

		/**
		 * Fill the countries dropdown with the countries of the selected sizechart.
		 */
		var _countries = [];
		angular.forEach(_sizechart.countries, function(v) {
			_countries.push({
				value: v,
				text: _countries_lookup[v]
			});
		});
		$scope.sizechart_countries = _countries;

		$timeout(function() {
			try {
				$scope.api_deviser_sizechart.select_none();
			} catch(e){}
		}, 0);

		/**
		 * If we already have the data of this sizechart, don't do anything else
		 */
		if($scope.product.sizechart.pristine === true && $scope.product.sizechart.short_id === _sizechart.short_id) return;

		/**
		 * We either don't have any data or the data that we have doesn't match the data from the selected sizechart,
		 * so we must fill the new data.
		 */
		console.log("CLEARING VALUES 2");
		$scope.product.sizechart.metric_unit = _sizechart.metric_unit;
		$scope.product.sizechart.columns = angular.copy(_sizechart.columns);
		$scope.product.sizechart.values = [];
		$scope.product.sizechart.short_id = _sizechart.short_id;
		$scope.product.sizechart.pristine = true;
	});











	/**
	 * Keep an eye on the values of the sizechart. If it changes,
	 * mark the sizechart as pristine or not, accordingly.
	 * Also, make sure to fill the available_sizes dropdown (if any sizes are available).
	 */
	$scope.$watch("[product.sizechart.values, sizecharts]", function(_new, _old) {
		if($scope.product.sizechart.values && $scope.product.sizechart.values.length === 0) return;

		var _tmp_values = $scope._getSizechartValuesForCountry();
		if(_tmp_values === undefined) {
			return;
		} else if(_tmp_values === null || angular.equals([], _new[1])) {
			/**
			 * If we are here it means that the selected categories changed in such a way that the sizechart that
			 * we had selected isn't possible to keep anymore and should be removed.
			 */
			$scope.product.sizechart = {};
		}

		$scope.product.sizechart.pristine = angular.equals($scope.product.sizechart.values, _tmp_values);

		/**
		 * Fill (or empty) the dropdown that allows to add sizes
		 */
		$scope.available_sizes = [];
		if($scope.product.sizechart.pristine === true) {
			/**
			 * If we have a pristine sizechart,the available_sizes dropdown will be empty
			 * (because all sizes are already in the sizechart), so don't do anything as we already
			 * emptied available_sizes
			 */
		} else if(!angular.equals([], _tmp_values)) {
			/**
			 * It seems that _tmp_values contains something, which means we can compare what we currently
			 * have in product.sizechart.values and _tmp_values, find the difference and fill the dropdown
			 * with that difference.
			 */
			var _sizes_in_original_sizechart = [];
			var _sizes_in_sizechart_table = [];

			angular.forEach(_tmp_values, function(row) {
				_sizes_in_original_sizechart.push(row[0]);
			});

			angular.forEach($scope.product.sizechart.values, function(row) {
				_sizes_in_sizechart_table.push(row[0]);
			});

			var _diff = _.difference(_sizes_in_original_sizechart, _sizes_in_sizechart_table);

			angular.forEach(_diff, function(size) {
				$scope.available_sizes.push({
					text: size,
					value: size
				});
			})
		}
	}, true);









	//Whenever the countries dropdown is filled, try to preselect the country of the product's sizechart
	$scope.$watch("sizechart_countries", function(_new, _old) {
		/**
		 * If both _new and _old are undefined, quit, Angular is still loading
		 */
		if(_new === undefined && _old === undefined) return;

		/**
		 * Something deleted all the countries in the dropdown. Stop listening to changes in the
		 * tmp_selected_sizechart_country model and quit.
		 */
		if(angular.isArray(_new) && _new.length === 0 && angular.isArray(_old) && _old.length > 0) {
			$scope.country_watched = $scope.country_watched !== undefined ? $scope.country_watched() : undefined;
			return;
		}

		//Watch the selected country and fill in the sizechart values table
		$scope.country_watched = $scope.$watch("tmp_selected_sizechart_country", function(_new, _old) {
			if(_new === _old) return;

			if(_new !== "" && $scope.product.sizechart.pristine === true) {
				$scope.product.sizechart.country = _new;
			}

			/**
			 * Empty the values of the sizachart only if the sizechart values are pristine.
			 */
			if(_new === "" && $scope.product.sizechart.pristine === true) {
				console.log("CLEARING VALUES 3");
				$scope.product.sizechart.values = [];
				return;
			}

			/**
			 * If there is a selected country, override the sizechart values with those of the country.
			 */
			if(_new !== "") {
				console.log("OVERRIDING VALUES 4");
				var _tmp_values = $scope._getSizechartValuesForCountry();
				if(_tmp_values !== undefined) {
					$scope.product.sizechart.values = _tmp_values;
				}
			}
		});

		//Try to select the current (if any) country in the dropdown
		$timeout(function() {
			$scope.api_sizechart_country.select($scope.product.sizechart.country);
		}, 0);

	});










	/**
	 * Get an array of values that contains:
	 * * All non-empty sizes of the currently selected country
	 * * All the values of the common sizechart columns (columns that don't belong to individual countries)
	 * @private
	 */
	$scope._getSizechartValuesForCountry = function() {
		/**
		 * If there isn't a short_id in the product.sizechart table, it means that this is a completely new product
		 * and the available_sizecharts dropdown shouldn't be populated.
		 * Also, if $scope.sizecharts is undefined or not an array, it means that we're still waiting for a $http callback.
		 */
		var _short_id = $scope.product.sizechart.short_id;
		if(_short_id === undefined || _short_id === "" || $scope.sizecharts === undefined || !angular.isArray($scope.sizecharts)) {
			return undefined;
		}

		/**
		 * If none of the sizecharts that we downloaded matches the short_id in our product.sizechart it means that categories
		 * changed, but the product.sizechart wasn't removed. We'll return "null" here so the caller knows to remove
		 * the product.sizechart data.
		 */
		var _sizechart = $scope.c_sizecharts.get(_short_id);
		if(_sizechart === undefined) {
			return null;
		}

		var _tmp_values = [];

		angular.forEach(_sizechart.values, function(_row) {
			var _tmp_value = _row.slice(_sizechart.countries.length);
			var _country_index = _sizechart.countries.indexOf($scope.product.sizechart.country);
			var _size = _row.slice(_country_index, _country_index + 1)[0];

			//Don't push rows without a size
			if(_size === "") return;
			_tmp_value.unshift(_size);
			_tmp_values.push(_tmp_value);
		});

		return _tmp_values;
	};











	/**
	 * React to changes in the dropdown containing custom sizecharts
	 */
	$scope.$watch("selected_deviser_sizechart", function(_new) {
		if(_new === undefined || _new.length !== 1) return;
		var _sizechart = _new[0];

		$timeout(function() {
			$scope.api_sizechart_country.select_none();
			$scope.api_sizechart.select_none();

			$timeout(function() {
				delete _sizechart["_id"];

				//TODO: remove when Angular Multi Select is fixed
				delete _sizechart["value"];
				delete _sizechart["check"];
				delete _sizechart["_check_time"];
				delete _sizechart[""];

				$scope.product.sizechart = _sizechart;
			}, 0);
		}, 0);

	});










	//Price & Stock table generator
	$scope.$watch("[product.options, product.sizechart, use_sizecharts]", function(_new, _old) {
		$scope.show_pricestock = false;
		/**
		 * If we're not sure if use_sizecharts is true or false, quit (we're waiting for $http callback)
		 */
		if($scope.use_sizecharts === undefined) {
			return;
		}

		/*
		 * If there are no tags selected, don't do anything.
		 */
		if(!angular.isObject($scope.product.options) || Object.keys($scope.product.options).length === 0) {
			return;
		}

		/*
		 * If there is no size chart selected (and/or country), don't do anything.
		 */
		if($scope.use_sizecharts === true) {
			if (!angular.isObject($scope.product.sizechart) || Object.keys($scope.product.sizechart).length === 0 ||
				!$scope.product.sizechart.hasOwnProperty("country") || $scope.product.sizechart.country === "") {
				return;
			}
		}

		console.log("start ================", _new, _old);

		/*
		 * Get all the tags that are currently selected
		 * ...and then store the IDs of the tags that should be used to generate the "Price & Stock" table.
		 */
		var tags = $scope.getTags(Object.keys($scope.product.options));
		var _tag_values = {};
		var _tags_for_ps_table = [];
		angular.forEach(tags, function(tag) {
			if(tag.stock_and_price === true) {
				_tags_for_ps_table.push(tag.short_id);
				_tag_values[tag.short_id] = angular.copy($scope.product.options[tag.short_id]);
			}
		});

		/*
		 * The user must add at least 1 combination of each required tag and
		 * all the combinations of all tags should contains values, meaning
		 * there can't be a combination without values.
		 */
		//TODO: Maybe this shouldn't be here?
		var _quit = false;
		angular.forEach($scope.product.options, function(values, tag_id) {
			if($scope.required_tags_ids.indexOf(tag_id) === -1) return;
			if(values.length === 0) _quit = true;
			angular.forEach(values, function(value) {
				if(angular.isArray(value) && value.length === 0) _quit = true;
				if(angular.isObject(value) && Object.keys(value).length === 0) _quit = true;
			});
		});
		if(_quit === true) return;

		/*
		 * If the special column "Size" is used (mainly, in 'Fashion'), add "size" to
		 * the header of the table and the first value of each row in product.sizechart, which
		 * contains the value of the size for the selected country.
		 */
		if($scope.use_sizecharts === true) {
			_tags_for_ps_table.unshift("size");
			_tag_values["size"] = [];

			angular.forEach($scope.product.sizechart.values, function(row) {
				_tag_values["size"].push(row[0]);
			});
		}

		/*
		 * We want to generate all the possible combinations in a certain order.
		 * If we're in 'use_sizecharts' mode, the special tag_id 'size' will be at
		 * position 0 in the _tag_ids array. If not, it doesn't matter.
		 */
		var data = [];
		angular.forEach(_tags_for_ps_table, function(tag_id) {
			data.push(_tag_values[tag_id]);
		});

		//This contains an array will all the possible combinations of tag values (and the size, if applies).
		var _ps_data = $scope.allPossibleCases(data);

		var _obj;
		var _price_stock = [];
		angular.forEach(_ps_data, function(row) {
			var _header = angular.copy(_tags_for_ps_table);
			_obj = {
				"options": {}
			};

			if($scope.use_sizecharts === true) {
				_obj.size = row.shift();
				_header.shift();
			}

			angular.forEach(_header, function(tag_id) {
				_obj.options[tag_id] = row.shift();
			});

			var match = null;
			if($scope.use_sizecharts === true) {
				match = $scope.find_option($scope.product.price_stock, _obj.options, _obj.size);
			} else {
				match = $scope.find_option($scope.product.price_stock, _obj.options);
			}

			console.log("MATCH", angular.copy(match));
			if(match !== null) {
				if($scope.use_sizecharts === true && match.size === _obj.size) {
					console.log("Pushing", angular.copy(match));
					_obj = angular.copy(match);
				}
			}
			_price_stock.push(_obj);
			_obj = null;

		});

		if($scope.use_sizecharts === true) {
			_tags_for_ps_table.shift();
		}
		$scope._ps_header = _tags_for_ps_table;
		console.log("changing price and stock", angular.copy(_price_stock));
		$scope.product.price_stock = _price_stock;
		$scope.show_pricestock = true;

		console.log("end ================");
	}, true);


	/**
	 * This is used to apply a given value to all the rows of the "Price & Stock" table.
	 * We just iterate over the rows of the price_stock array and apply the value to the passed key.
	 *
	 * NOTE: This is used for 'weight', 'stock' and 'price' fields.
	 */
	$scope.apply_to_all = function(field, value) {
		if(value === undefined) return;

		angular.forEach($scope.product.price_stock, function(row) {
			if(angular.isObject(row)) {
				row[field] = value;
			}
		});
	};

	/**
	 * This is used to insert a new size into the sizechart.values array.
	 */
	$scope.insertSizeInTable = function() {
		/**
		 * If there is no selected size, don't do anything.
		 */
		if($scope.tmp_selected_size === undefined || $scope.tmp_selected_size === "") return;

		var _values = [];
		var size = parseInt($scope.tmp_selected_size) || $scope.tmp_selected_size;

		/**
		 * Iterate over each row of the values array until the first element of each row (which is the size)
		 * is smaller than the size we're trying to insert. That way we'll insert it in the right place.
		 */
		var _inserted = false;
		angular.forEach($scope.product.sizechart.values, function(_row) {
			/**
			 * The parseInt magic is required because a size can be a number of a number followed by some text.
			 * In the second case, "12 (XL)" would turn out to be "smaller" than "2 (XXS", which is not true
			 */
			var _size = parseInt(_row[0]) || _row[0];
			if(_inserted === false && _size > size) {
				_inserted = true;
				var _new_row = Array.apply(null, Array(_row.length)).map(Number.prototype.valueOf,0);
				_new_row[0] = $scope.tmp_selected_size;
				_values.push(_new_row);
			}
			_values.push(_row);
		});

		/**
		 * If we didn't inserted the size it means that the size is bigger than all other sizes and it should
		 * be inserted at the end.
		 */
		if(_inserted === false) {
			var _short_id = $scope.product.sizechart.short_id;
			var _sizechart = $scope.c_sizecharts.get(_short_id);
			if(_sizechart === undefined) {
				//Something really bad happened. Quit before we break something.
				return;
			}

			var _len = _sizechart.values[0].length - _sizechart.countries.length + 1;
			var _new_row = Array.apply(null, Array(_len)).map(Number.prototype.valueOf,0);
			_new_row[0] = $scope.tmp_selected_size;
			_values.push(_new_row);
		}

		/**
		 * Set the new values.
		 */
		$scope.product.sizechart.values = _values;
	};

	/**
	 * This is used to delete a size from the sizechart.values array.
	 */
	$scope.deleteSizeFromTable = function(row_index) {
		$scope.product.sizechart.values.splice(row_index, 1);
	};

	$scope.save_sizechart = function() {
		toastr.info("Not implemented yet");
	};

	$scope.getTagOption = function(tag, value) {
		var _match = null;
		angular.forEach(tag.options, function(option) {
			if(option.value === value) _match = option;
		});
		return _match;
	};

	$scope.getTags = function(tag_ids) {
		var tags = [];
		angular.forEach(tag_ids, function(tag_id) {
			var tag = $scope.getTag(tag_id);
			if(Object.keys(tag).length > 0) tags.push(tag);
		});
		return tags;
	};

	$scope.getCategory = function(short_id) {
		var res = $scope.c_categories.get(short_id);
		if(res !== undefined) return res;

		angular.forEach(_categories, function(category) {
			$scope.c_categories.put(category.short_id, category);
		});

		return $scope.c_categories.get(short_id);
	};

	$scope.getTag = function(tag_id) {
		var res = $scope.c_tags.get(tag_id);
		if(res !== undefined) return res;

		angular.forEach(_tags, function(tag) {
			$scope.c_tags.put(tag.short_id, tag);
		});

		return $scope.c_tags.get(tag_id);
	};

	//Get all tags that belong to a category
	$scope.getTagsInCategory = function(category_id) {
		var res = $scope.c_tagsInCategory.get(category_id);
		if(res !== undefined) return res;

		var _tmp = {};
		angular.forEach(_tags, function(tag) {
			angular.forEach(tag.categories, function(_category_id) {
				if(!_tmp.hasOwnProperty(_category_id)) _tmp[_category_id] = [];

				_tmp[_category_id].push(tag);
			});
		});

		angular.forEach(_tmp, function(tags, _category_id) {
			$scope.c_tagsInCategory.put(_category_id, tags);
		});

		if(!_tmp.hasOwnProperty(category_id)) {
			_tmp[category_id] = [];
			$scope.c_tagsInCategory.put(category_id, []);
		}

		return _tmp[category_id];
	};

	//Sort tags
	$scope.sortTags = function(tags) {
		tags.sort(function(a, b){
			return a > b;
		});
	};

	//Combinations generator for price & stock table
	$scope.allPossibleCases = function(array, result, index) {
		if (!result) {
			result = [];
			index = 0;
			array = array.map(function(element) {
				return element.push ? element : [element];
			});
		}
		if (index < array.length) {
			array[index].forEach(function(element) {
				var a = array.slice(0);
				a.splice(index, 1, [element]);
				$scope.allPossibleCases(a, result, index + 1);
			});
		} else {
			var _result_set = [];
			array.forEach(function(v) {
				_result_set.push(v[0]);
			});
			result.push(_result_set);
		}

		return result;
	};

	/**
	 * Deeply compare objects. Returns true of both object are identical or false if they differ.
	 * @param x
	 * @param y
	 * @returns {boolean}
	 */
	$scope.deepCompare = function(x, y){
		if (x === null || x === undefined || y === null || y === undefined) { return x === y; }
		// after this just checking type of one would be enough
		if (x.constructor !== y.constructor) { return false; }
		// if they are functions, they should exactly refer to same one (because of closures)
		if (x instanceof Function) { return x === y; }
		// if they are regexps, they should exactly refer to same one (it is hard to better equality check on current ES)
		if (x instanceof RegExp) { return x === y; }
		if (x === y || x.valueOf() === y.valueOf()) { return true; }
		if (Array.isArray(x) && x.length !== y.length) { return false; }

		// if they are dates, they must had equal valueOf
		if (x instanceof Date) { return false; }

		// if they are strictly equal, they both need to be object at least
		if (!(x instanceof Object)) { return false; }
		if (!(y instanceof Object)) { return false; }

		// recursive object equality check
		var p = Object.keys(x);
		return Object.keys(y).every(function (i) { return p.indexOf(i) !== -1; }) &&
			p.every(function (i) { return $scope.deepCompare(x[i], y[i]); });
	};

	//Create a combination tag (if it doesn't exist already) and add a new, empty, combination.
	$scope.create_product_option = function(tag_id) {
		if(!$scope.product.options.hasOwnProperty(tag_id)) {
			$scope.product.options[tag_id] = [];
		}
		var tag = $scope.getTag(tag_id);

		/*
		 * If the tag is a dropdown, we insert a simple empty array.
		 * Else, if the tag is a freetext, we insert an array of as many
		 * objects as the tag has.
		 */
		if(tag.type === 0) {
			$scope.product.options[tag_id].push([]);
		} else if(tag.type === 1) {
			var option = [];
			angular.forEach(tag.options, function(_option) {
				option.push({
					metric_unit: $scope.mus[_option.metric_type].sub[0].value,
					value: 0
				})
			});
			$scope.product.options[tag_id].push(option);
		} else {
			// Future types of tags?
		}

	};

	//Remove a tag combination or the entire tag if no combinations are left.
	$scope.remove_product_option = function(option_id, index) {
		$scope.product.options[option_id].splice(index, 1);
	};

	/**
	 * Check if the photo at the given index is set at the product's main photo.
	 */
	$scope.is_main_photo = function(index) {
		var _key = "main_product_photo";
		return $scope.product.media.photos[index][_key];
	};

	/**
	 * Set a photo as the product's main photo.
	 */
	$scope.set_main_photo = function(index) {
		var _key = "main_product_photo";
		angular.forEach($scope.product.media.photos, function(photo) {
			delete photo[_key];
		});

		$scope.product.media.photos[index][_key] = true;
	};

	/*
	 * Functions required for comparing and extracting tag values in a product
	 */

	$scope.array_has_primitive_values = function(arr) {
		var _sw = true;
		angular.forEach(arr, function(v) {
			if(!angular.isNumber(v) && !angular.isString(v)) _sw = false;
		});
		return _sw;
	};

	$scope.array_has_object_values = function(arr) {
		var _sw = true;
		angular.forEach(arr, function(v) {
			if(!angular.isObject(v)) _sw = false;
		});
		return _sw;
	};

	/*
	 * Check if both arrays contain the same values. Order doesn't matter.
	 */
	$scope.array_compare = function(a, b) {
		if(!angular.isArray(a) || !angular.isArray(b)) return false;
		if (a.length != b.length) return false;
		for (var i = 0; i < b.length; i++) {
			if (a.indexOf(b[i]) === -1) return false;
		}
		return true;
	};

	$scope.find_option = function(arr, prop, size) {
		if(!angular.isObject(prop)) return;
		var _keys = Object.keys(prop);
		size = size || null;

		var _found = null;

		//Iterate over each option object
		angular.forEach(arr, function(tag) {
			if($scope.array_compare(Object.keys(tag.options), _keys) === false) return;

			var _sw = true;

			//Iterate over each key/value in the option
			angular.forEach(tag.options, function(values, tag_id) {
				if ($scope.array_has_primitive_values(values)) {
					//console.log("looking for sizes", size, tag.size);
					if(!$scope.array_compare(values, prop[tag_id])) {
						_sw = false;
					} else if (size !== null && size !== tag.size) {
						_sw = false;
					}
				} else if ($scope.array_has_object_values(values)) {
					if(!angular.equals(values, prop[tag_id])) {
						_sw = false;
					} else if (size !== null && size !== tag.size) {
						_sw = false;
					}
				} else {
					_sw = false;
				}
			});

			if(_sw === true) {
				_found = angular.copy(tag);
			}
		});

		return _found;
	};

	$scope.delete_photo = function(index, event) {
		event.stopPropagation();
		var _photo = $scope.product.media.photos[index];

		/**
		 * Remove the photo from the server (if it is uploaded)
		 * and from the photos array
		 */
		if(_photo.not_uploaded === undefined) {
			//Remove from the server $http...
			$http({
				method: "POST",
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'X-CSRF-Token': yii.getCsrfToken()
				},
				url: _delete_product_photo_url,
				data: {
					"photo_name": _photo.name
				}
			}).success(function(data, status, headers, config) {
				toastr.success("Photo removed successfully!");
			}).error(function(data, status, headers, config) {
				toastr.error("Couldn't remove photo!", data);
			});
		}

		$scope.product.media.photos.splice(index, 1);
	};

	$scope.$watch("shadow_photos", function(_new) {
		if(!angular.isArray(_new)) return;

		var photo;
		while( (photo = _new.shift()) !== undefined) {
			$scope.product.media.photos.push({
				name: "",
				tags: [],
				blob: photo,
				not_uploaded: true
			});
		}

	});

	$scope.save = function() {

		console.log($scope.product);

		var do_save = _.after($scope.product.media.photos.length, _.once(function() {
			/**
			 * Make a copy of the actual product and delete the "blob" property, which we
			 * use only locally to hold the data of the image.
			 */
			var _shadow_product = angular.copy($scope.product);
			angular.forEach(_shadow_product.media.photos, function(product) {
				delete product.blob;
			});

			$product.modify("POST", _shadow_product).then(function(data) {
				toastr.success("Product saved successfully!");
			}, function(err) {
				toastr.error("Failed saving product!", err);
			});
		}));

		angular.forEach($scope.product.media.photos, function(photo, index, photos) {
			if(photo.not_uploaded !== true) {
				do_save();
				return;
			}

			delete photo.not_uploaded;

			Upload.upload({
				headers : {
					'X-CSRF-TOKEN' : yii.getCsrfToken()
				},
				url: _upload_product_photo_url,
				file: photo.blob,
				fields: {
					'data': {
						'name': photo.name,
						'tags': photo.tags
					}
				}
			}).progress(function(e) {
				var progressPercentage = parseInt(100.0 * e.loaded / e.total);
				//console.log('progress: ' + progressPercentage + '% ' + e.config.file.name);
				photo.progress = progressPercentage;
			}).success(function(data, status, header, config) {
				//console.log('file ' + config.file.name + 'uploaded. Response: ' + data);
				photos[index] = angular.copy(data.media.photos.pop());
				toastr.success("Uploaded successfully product photo", config.file.name);
			}).error(function(err) {
				photos[index].not_uploaded = true;
				toastr.error("Error while uploading product photo", err)
			}).finally(function() {
				delete photos[index]["progress"];
				do_save();
			});
		});

		/**
		 * Call do_save(), in case no photos were selected by the user.
		 */
		if($scope.product.media.photos.length === 0) {
			do_save();
		}

	};

}]);

todevise.directive('filterInput', function(){
	return {
		require: '?ngModel',
		scope: {
			filterInputRegex: '@'
		},
		link: function(scope, element, attrs, modelCtrl) {
			if(!modelCtrl) return;
			modelCtrl.$parsers.push(function (inputValue) {
				if (inputValue == undefined) return '';
				var r = new RegExp(scope.filterInputRegex, "g");

				var transformedInput = inputValue.replace(r, '');
				if (transformedInput != inputValue) {
					modelCtrl.$setViewValue(transformedInput);
					modelCtrl.$render();
				}

				return transformedInput;
			});
		}
	};
});
