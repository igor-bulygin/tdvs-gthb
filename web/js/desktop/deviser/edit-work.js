var todevise = angular.module('todevise', ['ui.bootstrap', 'angular-multi-select', 'angular-unit-converter', 'angular-img-dl', 'global-deviser', 'global-desktop', 'api', "ngFileUpload", "ngImgCrop", 'ui.bootstrap.datetimepicker']);
var global_deviser = angular.module('global-deviser');

todevise.controller('productCtrl', ["$scope", "$timeout", "$sizechart", "$product", "$category_util", "toastr", "$modal", "Upload", function($scope, $timeout, $sizechart, $product, $category_util, toastr, $modal, Upload) {
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
	$scope.tmp_selected_lang_name = "";
	$scope.tmp_selected_lang_desc = "";

	//Required for the name and description dropdowns
	$scope.langs = [];
	angular.forEach(_langs, function(v, k) {
		$scope.langs.push({ "key": k, "value": v });
	});

	$scope.$watch("product.options", function(_new) {
		$scope.dump = angular.toJson(_new, 4);
	}, true);

	$scope.$watch("product.sizechart", function(_new) {
		$scope.dump_sizechart = angular.toJson(_new, 4);
	}, true);

	//Sort by path length
	_categories = $category_util.sort(_categories);
	$scope.categories = $category_util.create_tree(_categories);

	$scope.$watch("product.categories", function(_new, _old) {
		/*
		 * Once the categories of a product have changed, we must iterate over
		 * each category and find all the tags it is assigned to.
		 */
		var _tags_from_categories = [];
		var _tmp_tags_ids = []; //We'll use this to avoid duplicate tags

		var _use_sizecharts = false;
		var _use_prints = false;

		angular.forEach(_new, function(short_id) {
			//Generate the tags that must be shown
			var __category = jsonpath.query(_categories, "$..[?(@.short_id=='" + short_id + "')]")[0];
			var __tags = $scope.getTagsInCategory(short_id);

			angular.forEach(__tags, function(_tag) {
				if(_tmp_tags_ids.indexOf(_tag.short_id) !== -1) return;

				_tags_from_categories.push(_tag);
				_tmp_tags_ids.push(_tag.short_id);
			});

			//Check if the category contains a size chart
			if(__category.sizecharts === true) _use_sizecharts = true;

			//Check if the category contains prints
			if(__category.prints === true) _use_prints = true

		});

		$scope.sortTags(_tags_from_categories);
		$scope.tags_from_categories = _tags_from_categories;
		$scope.use_sizecharts = _use_sizecharts;

		//Fetch all sizecharts that belong to _tmp_tags_ids
		if(_use_sizecharts) {
			$sizechart.get({
				"category": {
					"in": _new
				}
			}).then(function(_sizecharts) {
				$scope.sizecharts = _sizecharts;
				console.log(_sizecharts);
			});
		}

		$scope.use_prints = _use_prints;

		console.log("Tags", $scope.tags_from_categories);
	});

	//React to changes in the dropdown containing normal size charts
	$scope.$watch("selected_sizechart", function(_new, _old) {
		if(_new === undefined || _new.length !== 1){
			if(_old !== undefined) {
				$scope.product.sizechart.values = [];
			}
			$scope.sizechart_countries = [];
			return;
		}

		var _sizechart = _new[0];

		var _countries = [];
		angular.forEach(_sizechart.countries, function(v) {
			_countries.push({
				value: v,
				text: _countries_lookup[v]
			});
		});

		$timeout(function() {
			$scope.api_deviser_sizechart.select_none();
		}, 0);

		$scope.product.sizechart.metric_unit = _sizechart.metric_unit;
		$scope.product.sizechart.columns = angular.copy(_sizechart.columns);
		$scope.product.sizechart.values = [];

		$scope.sizechart_countries = _countries;
		$scope.available_sizes = [];
	});

	//Whenever the countries dropdown is filled, try to preselect the country of the product's sizechart
	$scope.$watch("sizechart_countries", function(_new, _old) {
		if(_new === _old || _new === undefined || _new.length === 0) {
			return;
		}

		$scope.country_watched = $scope.country_watched !== undefined ? $scope.country_watched() : undefined;

		//Watch the selected country and fill in the sizechart values table
		$scope.country_watched = $scope.$watch("tmp_selected_sizechart_country", function(_new, _old) {
			if(_new === _old) return;
			$scope.product.sizechart.country = _new;

			var _tmp_values = [];
			angular.forEach($scope.selected_sizechart[0].values, function(_row) {
				var _tmp_value = _row.slice($scope.selected_sizechart[0].countries.length);
				var _country_index = $scope.selected_sizechart[0].countries.indexOf($scope.product.sizechart.country);
				var _size = _row.slice(_country_index, _country_index + 1)[0];

				//Don't push rows without a size
				if(_size === "") return;
				_tmp_value.unshift(_size);
				_tmp_values.push(_tmp_value);
			});

			$scope.product.sizechart.values = _tmp_values;
		});

		//Try to select the current (if any) country in the dropdown
		$timeout(function() {
			$scope.api_sizechart_country.select($scope.product.sizechart.country);
		}, 0);

	});

	//If the deviser selects
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

				$scope.product.sizechart = angular.copy(_sizechart);
				$scope.available_sizes = [];

			}, 0);
		}, 0);

	});

	//Price & Stock table generator
	$scope.$watch("[product.options, product.sizechart]", function(_new, _old) {
		//If there are no tags selected, don't do anything.
		if(!angular.isObject($scope.product.options) || Object.keys($scope.product.options).length === 0) {
			return;
		}

		var _options_keys = Object.keys($scope.product.options);
		$scope.sortTags(_options_keys);
		var _options = angular.copy($scope.product.options);

		if($scope.use_sizecharts === true) {
			if(!angular.isObject($scope.product.sizechart) || Object.keys($scope.product.sizechart).length === 0) {
				return;
			}

			_options_keys.unshift("size");
			_options["size"] = [];

			angular.forEach($scope.product.sizechart.values, function(row) {
				_options["size"].push(row[0]);
			});
		}

		/*
		 * We want to generate all the possible combinations in a certain order.
		 * That is why we do the sortTags() call earlier.
		 */
		var data = [];
		angular.forEach(_options_keys, function(tag_id) {
			data.push(_options[tag_id]);
		});

		//This contains an array will all the possible combinations of tag values (and the size, if applies).
		var _ps_data = $scope.allPossibleCases(data);

		var _obj;
		var _price_stock = [];
		angular.forEach(_ps_data, function(row) {
			_header = angular.copy(_options_keys);
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

			if(match !== null) {
				if($scope.use_sizecharts === true && match.size === _obj.size) {
					_obj = angular.copy(match);
				}
			}
			_price_stock.push(_obj);
			_obj = null;

		});

		if($scope.use_sizecharts === true) {
			_options_keys.shift();
		}
		$scope._ps_header = _options_keys;
		$scope.product.price_stock = _price_stock;
	}, true);


	$scope.insertSizeInTable = function() {
		/* The parseInt || <var> magic is required because a size value can be a number
		 * or a string. If a number, then be just parseInt it either way. If a string,
		 * then we compare the string and insert in alphabetical order.
		 */
		if($scope.tmp_selected_size === undefined || $scope.tmp_selected_size === "") return;

		var _values = [];

		var size = parseInt($scope.tmp_selected_size) || $scope.tmp_selected_size;
		var _inserted = false;
		angular.forEach($scope.product.sizechart.values, function(_row) {
			var _size = parseInt(_row[0]) || _row[0];
			if(_inserted === false && _size > size) {
				_inserted = true;
				var _new_row = Array.apply(null, Array(_row.length)).map(Number.prototype.valueOf,0);
				_new_row[0] = size;
				_values.push(_new_row);
			}
			_values.push(_row);
		});

		if(_inserted === false) {
			var _new_row = Array.apply(null, Array($scope.product.sizechart.values[0].length)).map(Number.prototype.valueOf,0);
			_new_row[0] = size;
			_values.push(_new_row);
		}

		var _available_sizes = [];
		angular.forEach($scope.available_sizes, function(_size) {
			if(_size.value !== $scope.tmp_selected_size) _available_sizes.push(_size);
		});
		$scope.available_sizes = _available_sizes;

		$scope.product.sizechart.values = _values;

		$timeout(function() {
			$scope.api_available_sizes.select_none();
		}, 0);
	};

	$scope.deleteSizeFromTable = function(row_index) {
		var _row = $scope.product.sizechart.values[row_index];
		var _available_sizes = [];

		//The first value of the row is the size
		var size = parseInt(_row[0]) || _row[0];
		var _inserted = false;
		angular.forEach($scope.available_sizes, function(_size) {
			_val = parseInt(_size.value) || _size.value;
			if(_inserted === false && _val > size) {
				_inserted = true;
				_available_sizes.push({
					text: _row[0],
					value: _row[0]
				});
			}
			_available_sizes.push(_size);
		});

		if(_inserted === false) {
			_available_sizes.push({
				text: _row[0],
				value: _row[0]
			});
		}

		$scope.available_sizes = angular.copy(_available_sizes);
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

	$scope.getTag = function(tag_id) {
		var __tag = jsonpath.query(_tags, "$..[?(@.short_id=='" + tag_id + "')]");
		__tag = __tag.length === 1 ? __tag[0] : {};
		return __tag;
	};

	//Get all tags that belong to a category
	$scope.getTagsInCategory = function(category_id) {
		var __tags = jsonpath.query(_tags, "$..[?(@.categories.indexOf('" + category_id + "')!==-1)]");
		return __tags.length > 0 ? __tags : [];
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

	//Deeply compare objects
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
	$scope.create_product_option = function(option_id) {
		if(!$scope.product.options.hasOwnProperty(option_id)) {
			$scope.product.options[option_id] = [];
		}
		$scope.product.options[option_id].push([]);
	};

	//Remove a tag combination or the entire tag if no combinations are left.
	$scope.remove_product_option = function(option_id, index) {
		$scope.product.options[option_id].splice(index, 1);

		if($scope.product.options[option_id].length === 0) {
			delete $scope.product.options[option_id];
		}
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

	$scope.save = function() {

		//TODO: Asegurarse de guardar solo los nuevos tags en las opciones del producto, borrando las que se han deseleccionado.

		$product.modify("POST", $scope.product).then(function() {
			toastr.success("Product saved successfully!");
		}, function(err) {
			toastr.error("Failed saving product!", err);
		});

		/*
		 if($scope.headerphoto && $scope.headerphoto.length === 1) {
		 Upload.upload({
		 headers : {
		 'X-CSRF-TOKEN' : yii.getCsrfToken()
		 },
		 url: '/upload-header-photo/',
		 file: $scope.headerphoto[0]
		 }).progress(function(e) {
		 //var progressPercentage = parseInt(100.0 * e.loaded / e.total);
		 //console.log('progress: ' + progressPercentage + '% ' + e.config.file.name);
		 }).success(function(data, status, header, config) {
		 //console.log('file ' + config.file.name + 'uploaded. Response: ' + data);
		 $scope.deviser = data;
		 toastr.success("Uploaded successfully headerphoto photo", config.file.name);
		 }).error(function(err) {
		 toastr.error("Error while uploading headerphoto photo", err)
		 });
		 }
		 */

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
