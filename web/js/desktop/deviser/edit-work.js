var todevise = angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'angular-multi-select', 'angular-unit-converter', 'angular-img-dl', 'global-deviser', 'global-desktop', 'global', 'api', "ngFileUpload", "ngImgCrop", 'ui.bootstrap.datetimepicker', 'angular-slugifier']);
var global_deviser = angular.module('global-deviser');

todevise.controller('productCtrl', ["$rootScope", "$scope", "$timeout", "$sizechart", "$product", "$category_util", "toastr", "$uibModal", "Upload", "$http", "$cacheFactory", function($rootScope, $scope, $timeout, $sizechart, $product, $category_util, toastr, $uibModal, Upload, $http, $cacheFactory) {

	/*
	 * Bootstrapped values
	 */
	$scope.lang = _lang;
	$scope.deviser = _deviser;
	$scope.product = _product;
	$scope.original_product = JSON.parse(JSON.stringify(_product));
	$scope.mus = _mus;
	$scope.countries = _countries;
	$scope.countries_lookup = _countries_lookup;
	$scope.currencies = _currencies;
	$scope.deviser_sizecharts = _deviser_sizecharts;

	$scope.tmp_selected_sizechart_size = undefined;

	$scope.c_tags = $cacheFactory("tags");
	$scope.c_categories = $cacheFactory("categories");
	$scope.c_sizecharts = $cacheFactory("sizecharts");
	$scope.c_tags_options = $cacheFactory("tags_options");
	$scope.c_tagsInCategory = $cacheFactory("tagsInCategory");
	$scope.c_sizechartsInCategory = $cacheFactory("sizechartsInCategory");

	/*
	██   ██ ███████ ██      ██████  ███████ ██████      ███████ ██    ██ ███    ██  ██████ ████████ ██  ██████  ███    ██ ███████
	██   ██ ██      ██      ██   ██ ██      ██   ██     ██      ██    ██ ████   ██ ██         ██    ██ ██    ██ ████   ██ ██
	███████ █████   ██      ██████  █████   ██████      █████   ██    ██ ██ ██  ██ ██         ██    ██ ██    ██ ██ ██  ██ ███████
	██   ██ ██      ██      ██      ██      ██   ██     ██      ██    ██ ██  ██ ██ ██         ██    ██ ██    ██ ██  ██ ██      ██
	██   ██ ███████ ███████ ██      ███████ ██   ██     ██       ██████  ██   ████  ██████    ██    ██  ██████  ██   ████ ███████
	*/

	$scope.dump = function(obj) {
		return angular.toJson(obj, 4);
	};

	/*
	 █████  ██████  ██████  ██   ██    ██     ████████  ██████       █████  ██      ██
	██   ██ ██   ██ ██   ██ ██    ██  ██         ██    ██    ██     ██   ██ ██      ██
	███████ ██████  ██████  ██     ████          ██    ██    ██     ███████ ██      ██
	██   ██ ██      ██      ██      ██           ██    ██    ██     ██   ██ ██      ██
	██   ██ ██      ██      ███████ ██           ██     ██████      ██   ██ ███████ ███████
	*/
	$scope.apply_to_all = function(field, value) {
		if(value === undefined) return;

		angular.forEach($scope.product.price_stock, function(row) {
			if(angular.isObject(row)) {
				row[field] = value;
			}
		});
	};

	/*
	 █████  ██████  ██████  ██   ██    ██     ████████  ██████      ███████  █████  ███    ███ ███████     ███████ ██ ███████ ███████
	██   ██ ██   ██ ██   ██ ██    ██  ██         ██    ██    ██     ██      ██   ██ ████  ████ ██          ██      ██    ███  ██
	███████ ██████  ██████  ██     ████          ██    ██    ██     ███████ ███████ ██ ████ ██ █████       ███████ ██   ███   █████
	██   ██ ██      ██      ██      ██           ██    ██    ██          ██ ██   ██ ██  ██  ██ ██               ██ ██  ███    ██
	██   ██ ██      ██      ███████ ██           ██     ██████      ███████ ██   ██ ██      ██ ███████     ███████ ██ ███████ ███████
	*/
	$scope.apply_to_same_size = function (size, field, value) {
		if(value === undefined) return;

		angular.forEach($scope.product.price_stock, function(row) {
			if(angular.isObject(row)) {
				if (row.options.size === size) {
					row[field] = value;
				}
			}
		});
	}

	/*
	██ ███████     ███    ███  █████  ██ ███    ██     ██████  ██   ██  ██████  ████████  ██████
	██ ██          ████  ████ ██   ██ ██ ████   ██     ██   ██ ██   ██ ██    ██    ██    ██    ██
	██ ███████     ██ ████ ██ ███████ ██ ██ ██  ██     ██████  ███████ ██    ██    ██    ██    ██
	██      ██     ██  ██  ██ ██   ██ ██ ██  ██ ██     ██      ██   ██ ██    ██    ██    ██    ██
	██ ███████     ██      ██ ██   ██ ██ ██   ████     ██      ██   ██  ██████     ██     ██████
	*/
	$scope.is_main_photo = function(index) {
		var _key = "main_product_photo";
		return $scope.product.media.photos[index][_key];
	};

	/*
	███████ ███████ ████████     ███    ███  █████  ██ ███    ██     ██████  ██   ██  ██████  ████████  ██████
	██      ██         ██        ████  ████ ██   ██ ██ ████   ██     ██   ██ ██   ██ ██    ██    ██    ██    ██
	███████ █████      ██        ██ ████ ██ ███████ ██ ██ ██  ██     ██████  ███████ ██    ██    ██    ██    ██
	     ██ ██         ██        ██  ██  ██ ██   ██ ██ ██  ██ ██     ██      ██   ██ ██    ██    ██    ██    ██
	███████ ███████    ██        ██      ██ ██   ██ ██ ██   ████     ██      ██   ██  ██████     ██     ██████
	*/
	$scope.set_main_photo = function(index) {
		var _key = "main_product_photo";
		angular.forEach($scope.product.media.photos, function(photo) {
			delete photo[_key];
		});

		$scope.product.media.photos[index][_key] = true;
	};

	/*
	██████  ███████ ██      ███████ ████████ ███████     ██████  ██   ██  ██████  ████████  ██████
	██   ██ ██      ██      ██         ██    ██          ██   ██ ██   ██ ██    ██    ██    ██    ██
	██   ██ █████   ██      █████      ██    █████       ██████  ███████ ██    ██    ██    ██    ██
	██   ██ ██      ██      ██         ██    ██          ██      ██   ██ ██    ██    ██    ██    ██
	██████  ███████ ███████ ███████    ██    ███████     ██      ██   ██  ██████     ██     ██████
	*/
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
				data: JSON.stringify({
					"photo_name": _photo.name
				})
			}).then(function(resp) {
				toastr.success("Photo removed successfully!");
			}, function(err) {
				toastr.error("Couldn't remove photo!", err);
			});
		}

		$scope.product.media.photos.splice(index, 1);
	};

	/*
	 ██████  ███████ ████████     ████████  █████   ██████
	██       ██         ██           ██    ██   ██ ██
	██   ███ █████      ██           ██    ███████ ██   ███
	██    ██ ██         ██           ██    ██   ██ ██    ██
	 ██████  ███████    ██           ██    ██   ██  ██████
	*/
	$scope.getTag = function(tag_id) {
		var res = $scope.c_tags.get(tag_id);
		if(res !== undefined) return res;

		angular.forEach(_tags, function(tag) {
			$scope.c_tags.put(tag.short_id, tag);
		});

		return $scope.c_tags.get(tag_id);
	};

	/*
	 ██████  ███████ ████████     ████████  █████   ██████       ██████  ██████  ████████ ██  ██████  ███    ██
	██       ██         ██           ██    ██   ██ ██           ██    ██ ██   ██    ██    ██ ██    ██ ████   ██
	██   ███ █████      ██           ██    ███████ ██   ███     ██    ██ ██████     ██    ██ ██    ██ ██ ██  ██
	██    ██ ██         ██           ██    ██   ██ ██    ██     ██    ██ ██         ██    ██ ██    ██ ██  ██ ██
	 ██████  ███████    ██           ██    ██   ██  ██████       ██████  ██         ██    ██  ██████  ██   ████
	*/
	$scope.getTagOption = function(tag, value) {
		var res = $scope.c_tags_options.get(tag.short_id + "" + value);
		if(res !== undefined) return res;

		angular.forEach(tag.options, function(option) {
			if(option.value === value) {
				$scope.c_tags_options.put(tag.short_id + "" + value, option);
			}
		});

		return $scope.c_tags_options.get(tag.short_id + "" + value);
	};

	/*
	 ██████  ███████ ████████      ██████  █████  ████████ ███████  ██████   ██████  ██████  ██    ██
	██       ██         ██        ██      ██   ██    ██    ██      ██    ██ ██       ██   ██  ██  ██
	██   ███ █████      ██        ██      ███████    ██    █████   ██    ██ ██   ███ ██████    ████
	██    ██ ██         ██        ██      ██   ██    ██    ██      ██    ██ ██    ██ ██   ██    ██
	 ██████  ███████    ██         ██████ ██   ██    ██    ███████  ██████   ██████  ██   ██    ██
	*/
	$scope.getCategory = function(short_id) {
		var res = $scope.c_categories.get(short_id);
		if(res !== undefined) return res;

		angular.forEach(_categories, function(category) {
			$scope.c_categories.put(category.short_id, category);
		});

		return $scope.c_categories.get(short_id);
	};

	/*
	███████  ██████  ██████  ████████     ████████  █████   ██████  ███████
	██      ██    ██ ██   ██    ██           ██    ██   ██ ██       ██
	███████ ██    ██ ██████     ██           ██    ███████ ██   ███ ███████
	     ██ ██    ██ ██   ██    ██           ██    ██   ██ ██    ██      ██
	███████  ██████  ██   ██    ██           ██    ██   ██  ██████  ███████
	*/
	$scope.sortTags = function(tags) {
		tags.sort(function(a, b){
			return a > b;
		});
	};

	/*
	 ██████  ███████ ████████     ████████  █████   ██████  ███████     ██ ███    ██      ██████  █████  ████████ ███████  ██████   ██████  ██████  ██    ██
	██       ██         ██           ██    ██   ██ ██       ██          ██ ████   ██     ██      ██   ██    ██    ██      ██       ██    ██ ██   ██  ██  ██
	██   ███ █████      ██           ██    ███████ ██   ███ ███████     ██ ██ ██  ██     ██      ███████    ██    █████   ██   ███ ██    ██ ██████    ████
	██    ██ ██         ██           ██    ██   ██ ██    ██      ██     ██ ██  ██ ██     ██      ██   ██    ██    ██      ██    ██ ██    ██ ██   ██    ██
	 ██████  ███████    ██           ██    ██   ██  ██████  ███████     ██ ██   ████      ██████ ██   ██    ██    ███████  ██████   ██████  ██   ██    ██
	*/
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

	/*
	 ██████  ███████ ████████     ███████ ██ ███████ ███████  ██████ ██   ██  █████  ██████  ████████
	██       ██         ██        ██      ██    ███  ██      ██      ██   ██ ██   ██ ██   ██    ██
	██   ███ █████      ██        ███████ ██   ███   █████   ██      ███████ ███████ ██████     ██
	██    ██ ██         ██             ██ ██  ███    ██      ██      ██   ██ ██   ██ ██   ██    ██
	 ██████  ███████    ██        ███████ ██ ███████ ███████  ██████ ██   ██ ██   ██ ██   ██    ██
	*/
	$scope.getSizechart = function (short_id) {
		var res = $scope.c_sizecharts.get(short_id);
		if(res !== undefined) return res;

		angular.forEach(_sizecharts, function(sizechart) {
			$scope.c_sizecharts.put(sizechart.short_id, sizechart);
		});

		return $scope.c_sizecharts.get(short_id);
	}

	/*
	 ██████  ███████ ████████     ███████ ██ ███████ ███████  ██████ ██   ██  █████  ██████  ████████ ███████     ██ ███    ██      ██████  █████  ████████ ███████  ██████   ██████  ██████  ██    ██
	██       ██         ██        ██      ██    ███  ██      ██      ██   ██ ██   ██ ██   ██    ██    ██          ██ ████   ██     ██      ██   ██    ██    ██      ██       ██    ██ ██   ██  ██  ██
	██   ███ █████      ██        ███████ ██   ███   █████   ██      ███████ ███████ ██████     ██    ███████     ██ ██ ██  ██     ██      ███████    ██    █████   ██   ███ ██    ██ ██████    ████
	██    ██ ██         ██             ██ ██  ███    ██      ██      ██   ██ ██   ██ ██   ██    ██         ██     ██ ██  ██ ██     ██      ██   ██    ██    ██      ██    ██ ██    ██ ██   ██    ██
	 ██████  ███████    ██        ███████ ██ ███████ ███████  ██████ ██   ██ ██   ██ ██   ██    ██    ███████     ██ ██   ████      ██████ ██   ██    ██    ███████  ██████   ██████  ██   ██    ██
	*/
	$scope.getSizechartsInCategory = function (category_id) {
		var res = $scope.c_sizechartsInCategory.get(category_id);
		if(res !== undefined) return res;

		var _tmp = {};
		angular.forEach(_sizecharts, function(sizechart) {
			angular.forEach(sizechart.categories, function(_category_id) {
				if(!_tmp.hasOwnProperty(_category_id)) _tmp[_category_id] = [];

				_tmp[_category_id].push(sizechart);
			});
		});

		angular.forEach(_tmp, function(sizechart, _category_id) {
			$scope.c_sizechartsInCategory.put(_category_id, sizechart);
		});

		if(!_tmp.hasOwnProperty(category_id)) {
			_tmp[category_id] = [];
			$scope.c_sizechartsInCategory.put(category_id, []);
		}

		return _tmp[category_id];
	}

	/*
	 ██████  ███████ ████████     ███████ ██ ███████ ███████  ██████ ██   ██  █████  ██████  ████████     ███████ ██ ███████ ███████ ███████     ███████  ██████  ██████       ██████  ██████  ██    ██ ███    ██ ████████ ██████  ██    ██
	██       ██         ██        ██      ██    ███  ██      ██      ██   ██ ██   ██ ██   ██    ██        ██      ██    ███  ██      ██          ██      ██    ██ ██   ██     ██      ██    ██ ██    ██ ████   ██    ██    ██   ██  ██  ██
	██   ███ █████      ██        ███████ ██   ███   █████   ██      ███████ ███████ ██████     ██        ███████ ██   ███   █████   ███████     █████   ██    ██ ██████      ██      ██    ██ ██    ██ ██ ██  ██    ██    ██████    ████
	██    ██ ██         ██             ██ ██  ███    ██      ██      ██   ██ ██   ██ ██   ██    ██             ██ ██  ███    ██           ██     ██      ██    ██ ██   ██     ██      ██    ██ ██    ██ ██  ██ ██    ██    ██   ██    ██
	 ██████  ███████    ██        ███████ ██ ███████ ███████  ██████ ██   ██ ██   ██ ██   ██    ██        ███████ ██ ███████ ███████ ███████     ██       ██████  ██   ██      ██████  ██████   ██████  ██   ████    ██    ██   ██    ██
	*/
	$scope.getSizechartSizesForCountry = function () {
		var sizechart = $scope.getSizechart($scope.product.sizechart.short_id);
		var index_of_country = sizechart.countries.indexOf($scope.product.sizechart.country);

		var sizes = [];
		angular.forEach(sizechart.values, function (value) {
			var size = value[index_of_country];
			if (size != "") sizes.push(size);
		});

		return sizes;
	};

	/*
	██████  ███████ ██████  ███    ███ ██    ██ ████████ ███████
	██   ██ ██      ██   ██ ████  ████ ██    ██    ██    ██
	██████  █████   ██████  ██ ████ ██ ██    ██    ██    █████
	██      ██      ██   ██ ██  ██  ██ ██    ██    ██    ██
	██      ███████ ██   ██ ██      ██  ██████     ██    ███████
	*/
	$scope.permute = function(arr, result, index) {
		if (!result) {
			result = [];
			index = 0;
			arr = arr.map(function(element) {
				return element.push ? element : [element];
			});
		}
		if (index < arr.length) {
			arr[index].forEach(function(element) {
				var a = arr.slice(0);
				a.splice(index, 1, [element]);
				$scope.permute(a, result, index + 1);
			});
		} else {
			var _result_set = [];
			arr.forEach(function(v) {
				_result_set.push(v[0]);
			});
			result.push(_result_set);
		}

		return result;
	};

	/*
	██████  ██    ██ ██ ██      ██████      ████████  █████   ██████  ███████
	██   ██ ██    ██ ██ ██      ██   ██        ██    ██   ██ ██       ██
	██████  ██    ██ ██ ██      ██   ██        ██    ███████ ██   ███ ███████
	██   ██ ██    ██ ██ ██      ██   ██        ██    ██   ██ ██    ██      ██
	██████   ██████  ██ ███████ ██████         ██    ██   ██  ██████  ███████
	*/
	$scope.build_tags = function () {
		var _tags_in_categories = [];
		var _tmp_tags_ids = []; //We'll use this to avoid duplicate tags

		$scope.required_tags_ids = [];
		angular.forEach($scope.product.categories, function(short_id) {
			var __tags = $scope.getTagsInCategory(short_id);

			//Don't push duplicated tags to _tags_in_categories
			//Check if tag is required and if so, push it to the required_tags_ids
			angular.forEach(__tags, function(_tag) {
				if(_tmp_tags_ids.indexOf(_tag.short_id) !== -1) return;

				if(_tag.required === true) {
					$scope.required_tags_ids.push(_tag.short_id);
				}

				_tags_in_categories.push(_tag);
				_tmp_tags_ids.push(_tag.short_id);
			});
		});

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
	};

	/*
	██   ██  █████  ███████     ██████  ██████  ██ ███    ██ ████████ ███████
	██   ██ ██   ██ ██          ██   ██ ██   ██ ██ ████   ██    ██    ██
	███████ ███████ ███████     ██████  ██████  ██ ██ ██  ██    ██    ███████
	██   ██ ██   ██      ██     ██      ██   ██ ██ ██  ██ ██    ██         ██
	██   ██ ██   ██ ███████     ██      ██   ██ ██ ██   ████    ██    ███████
	*/
	$scope.has_prints = function () {
		var sw = false;

		angular.forEach($scope.product.categories, function(short_id) {
			var __category = $scope.getCategory(short_id);
			if(__category.prints === true) sw = true;
		});

		return sw;
	};

	/*
	██   ██  █████  ███████     ███████ ██ ███████ ███████  ██████ ██   ██  █████  ██████  ████████ ███████
	██   ██ ██   ██ ██          ██      ██    ███  ██      ██      ██   ██ ██   ██ ██   ██    ██    ██
	███████ ███████ ███████     ███████ ██   ███   █████   ██      ███████ ███████ ██████     ██    ███████
	██   ██ ██   ██      ██          ██ ██  ███    ██      ██      ██   ██ ██   ██ ██   ██    ██         ██
	██   ██ ██   ██ ███████     ███████ ██ ███████ ███████  ██████ ██   ██ ██   ██ ██   ██    ██    ███████
	*/
	$scope.has_sizecharts = function () {
		var sw = false;

		angular.forEach($scope.product.categories, function(short_id) {
			var __category = $scope.getCategory(short_id);
			if(__category.sizecharts === true) sw = true;
		});

		return sw;
	};

	/*
	██████  ██    ██ ██ ██      ██████      ███████ ██ ███████ ███████  ██████ ██   ██  █████  ██████  ████████ ███████     ██████  ██████   ██████  ██████  ██████   ██████  ██     ██ ███    ██
	██   ██ ██    ██ ██ ██      ██   ██     ██      ██    ███  ██      ██      ██   ██ ██   ██ ██   ██    ██    ██          ██   ██ ██   ██ ██    ██ ██   ██ ██   ██ ██    ██ ██     ██ ████   ██
	██████  ██    ██ ██ ██      ██   ██     ███████ ██   ███   █████   ██      ███████ ███████ ██████     ██    ███████     ██   ██ ██████  ██    ██ ██████  ██   ██ ██    ██ ██  █  ██ ██ ██  ██
	██   ██ ██    ██ ██ ██      ██   ██          ██ ██  ███    ██      ██      ██   ██ ██   ██ ██   ██    ██         ██     ██   ██ ██   ██ ██    ██ ██      ██   ██ ██    ██ ██ ███ ██ ██  ██ ██
	██████   ██████  ██ ███████ ██████      ███████ ██ ███████ ███████  ██████ ██   ██ ██   ██ ██   ██    ██    ███████     ██████  ██   ██  ██████  ██      ██████   ██████   ███ ███  ██   ████
	*/
	$scope.build_sizecharts_dropdown = function () {
		var _sizecharts_in_categories = [];
		var _tmp_sizecharts_ids = []; //We'll use this to avoid duplicate sizecharts

		angular.forEach($scope.product.categories, function(short_id) {
			var __sizecharts = $scope.getSizechartsInCategory(short_id);

			//Don't push duplicated tags to _tags_in_categories
			//Check if tag is required and if so, push it to the required_tags_ids
			angular.forEach(__sizecharts, function(_sizechart) {
				if(_tmp_sizecharts_ids.indexOf(_sizechart.short_id) !== -1) return;

				_sizecharts_in_categories.push(_sizechart);
				_tmp_sizecharts_ids.push(_sizechart.short_id);
			});
		});

		$scope.sizecharts = _sizecharts_in_categories;
	};

	/*
	███████ ███    ███ ██████  ████████ ██    ██     ███████ ██ ███████ ███████  ██████ ██   ██  █████  ██████  ████████ ███████     ██████  ██████   ██████  ██████  ██████   ██████  ██     ██ ███    ██
	██      ████  ████ ██   ██    ██     ██  ██      ██      ██    ███  ██      ██      ██   ██ ██   ██ ██   ██    ██    ██          ██   ██ ██   ██ ██    ██ ██   ██ ██   ██ ██    ██ ██     ██ ████   ██
	█████   ██ ████ ██ ██████     ██      ████       ███████ ██   ███   █████   ██      ███████ ███████ ██████     ██    ███████     ██   ██ ██████  ██    ██ ██████  ██   ██ ██    ██ ██  █  ██ ██ ██  ██
	██      ██  ██  ██ ██         ██       ██             ██ ██  ███    ██      ██      ██   ██ ██   ██ ██   ██    ██         ██     ██   ██ ██   ██ ██    ██ ██      ██   ██ ██    ██ ██ ███ ██ ██  ██ ██
	███████ ██      ██ ██         ██       ██        ███████ ██ ███████ ███████  ██████ ██   ██ ██   ██ ██   ██    ██    ███████     ██████  ██   ██  ██████  ██      ██████   ██████   ███ ███  ██   ████
	*/
	$scope.empty_sizecharts_dropdown = function () {
		$rootScope.$broadcast('ams_do_uncheck_all', {
			name: 'ams_sizechart'
		});
		$scope.sizechart = [];
	};

	/*
	██████  ██    ██ ██ ██      ██████      ███████ ██ ███████ ███████  ██████ ██   ██  █████  ██████  ████████ ███████      ██████  ██████  ██    ██ ███    ██ ████████ ██████  ██ ███████ ███████     ██████  ██████   ██████  ██████  ██████   ██████  ██     ██ ███    ██
	██   ██ ██    ██ ██ ██      ██   ██     ██      ██    ███  ██      ██      ██   ██ ██   ██ ██   ██    ██    ██          ██      ██    ██ ██    ██ ████   ██    ██    ██   ██ ██ ██      ██          ██   ██ ██   ██ ██    ██ ██   ██ ██   ██ ██    ██ ██     ██ ████   ██
	██████  ██    ██ ██ ██      ██   ██     ███████ ██   ███   █████   ██      ███████ ███████ ██████     ██    ███████     ██      ██    ██ ██    ██ ██ ██  ██    ██    ██████  ██ █████   ███████     ██   ██ ██████  ██    ██ ██████  ██   ██ ██    ██ ██  █  ██ ██ ██  ██
	██   ██ ██    ██ ██ ██      ██   ██          ██ ██  ███    ██      ██      ██   ██ ██   ██ ██   ██    ██         ██     ██      ██    ██ ██    ██ ██  ██ ██    ██    ██   ██ ██ ██           ██     ██   ██ ██   ██ ██    ██ ██      ██   ██ ██    ██ ██ ███ ██ ██  ██ ██
	██████   ██████  ██ ███████ ██████      ███████ ██ ███████ ███████  ██████ ██   ██ ██   ██ ██   ██    ██    ███████      ██████  ██████   ██████  ██   ████    ██    ██   ██ ██ ███████ ███████     ██████  ██   ██  ██████  ██      ██████   ██████   ███ ███  ██   ████
	*/
	$scope.build_sizecharts_countries_dropdown = function () {
		var _countries = [];
		var sizechart = $scope.getSizechart($scope.product.sizechart.short_id);
		angular.forEach(sizechart.countries, function(v) {
			_countries.push({
				value: v,
				text: _countries_lookup[v]
			});
		});

		if (!angular.equals($scope.sizechart_countries, _countries)) {
			$scope.sizechart_countries = _countries;
		}
	};

	/*
	███████ ███    ███ ██████  ████████ ██    ██     ███████ ██ ███████ ███████  ██████ ██   ██  █████  ██████  ████████ ███████      ██████  ██████  ██    ██ ███    ██ ████████ ██████  ██ ███████ ███████     ██████  ██████   ██████  ██████  ██████   ██████  ██     ██ ███    ██
	██      ████  ████ ██   ██    ██     ██  ██      ██      ██    ███  ██      ██      ██   ██ ██   ██ ██   ██    ██    ██          ██      ██    ██ ██    ██ ████   ██    ██    ██   ██ ██ ██      ██          ██   ██ ██   ██ ██    ██ ██   ██ ██   ██ ██    ██ ██     ██ ████   ██
	█████   ██ ████ ██ ██████     ██      ████       ███████ ██   ███   █████   ██      ███████ ███████ ██████     ██    ███████     ██      ██    ██ ██    ██ ██ ██  ██    ██    ██████  ██ █████   ███████     ██   ██ ██████  ██    ██ ██████  ██   ██ ██    ██ ██  █  ██ ██ ██  ██
	██      ██  ██  ██ ██         ██       ██             ██ ██  ███    ██      ██      ██   ██ ██   ██ ██   ██    ██         ██     ██      ██    ██ ██    ██ ██  ██ ██    ██    ██   ██ ██ ██           ██     ██   ██ ██   ██ ██    ██ ██      ██   ██ ██    ██ ██ ███ ██ ██  ██ ██
	███████ ██      ██ ██         ██       ██        ███████ ██ ███████ ███████  ██████ ██   ██ ██   ██ ██   ██    ██    ███████      ██████  ██████   ██████  ██   ████    ██    ██   ██ ██ ███████ ███████     ██████  ██   ██  ██████  ██      ██████   ██████   ███ ███  ██   ████
	*/
	$scope.empty_sizecharts_countries_dropdown = function () {
		$rootScope.$broadcast('ams_do_uncheck_all', {
			name: 'ams_sizechart_country'
		});
		$scope.sizechart_countries = [];
	};

	/*
	██████  ██    ██ ██ ██      ██████      ███████ ██ ███████ ███████  ██████ ██   ██  █████  ██████  ████████ ███████     ███████ ██ ███████ ███████ ███████     ██████  ██████   ██████  ██████  ██████   ██████  ██     ██ ███    ██
	██   ██ ██    ██ ██ ██      ██   ██     ██      ██    ███  ██      ██      ██   ██ ██   ██ ██   ██    ██    ██          ██      ██    ███  ██      ██          ██   ██ ██   ██ ██    ██ ██   ██ ██   ██ ██    ██ ██     ██ ████   ██
	██████  ██    ██ ██ ██      ██   ██     ███████ ██   ███   █████   ██      ███████ ███████ ██████     ██    ███████     ███████ ██   ███   █████   ███████     ██   ██ ██████  ██    ██ ██████  ██   ██ ██    ██ ██  █  ██ ██ ██  ██
	██   ██ ██    ██ ██ ██      ██   ██          ██ ██  ███    ██      ██      ██   ██ ██   ██ ██   ██    ██         ██          ██ ██  ███    ██           ██     ██   ██ ██   ██ ██    ██ ██      ██   ██ ██    ██ ██ ███ ██ ██  ██ ██
	██████   ██████  ██ ███████ ██████      ███████ ██ ███████ ███████  ██████ ██   ██ ██   ██ ██   ██    ██    ███████     ███████ ██ ███████ ███████ ███████     ██████  ██   ██  ██████  ██      ██████   ██████   ███ ███  ██   ████
	*/
	$scope.build_sizecharts_sizes_dropdown = function () {
		var _available_sizes = [];

		var _sizes_for_country = $scope.getSizechartSizesForCountry();
		var _sizes_in_product = ($scope.product.sizechart.values || []).map(function (value) {
			return value[0];
		});

		var diff = _.difference(_sizes_for_country, _sizes_in_product);
		var _sizes = diff.map(function (v) { return {value: v} });
		if (!angular.equals($scope.available_sizes, _sizes)) {
			$scope.available_sizes = _sizes;
		}
	};

	/*
	███████ ███    ███ ██████  ████████ ██    ██     ███████ ██ ███████ ███████  ██████ ██   ██  █████  ██████  ████████ ███████     ███████ ██ ███████ ███████ ███████     ██████  ██████   ██████  ██████  ██████   ██████  ██     ██ ███    ██
	██      ████  ████ ██   ██    ██     ██  ██      ██      ██    ███  ██      ██      ██   ██ ██   ██ ██   ██    ██    ██          ██      ██    ███  ██      ██          ██   ██ ██   ██ ██    ██ ██   ██ ██   ██ ██    ██ ██     ██ ████   ██
	█████   ██ ████ ██ ██████     ██      ████       ███████ ██   ███   █████   ██      ███████ ███████ ██████     ██    ███████     ███████ ██   ███   █████   ███████     ██   ██ ██████  ██    ██ ██████  ██   ██ ██    ██ ██  █  ██ ██ ██  ██
	██      ██  ██  ██ ██         ██       ██             ██ ██  ███    ██      ██      ██   ██ ██   ██ ██   ██    ██         ██          ██ ██  ███    ██           ██     ██   ██ ██   ██ ██    ██ ██      ██   ██ ██    ██ ██ ███ ██ ██  ██ ██
	███████ ██      ██ ██         ██       ██        ███████ ██ ███████ ███████  ██████ ██   ██ ██   ██ ██   ██    ██    ███████     ███████ ██ ███████ ███████ ███████     ██████  ██   ██  ██████  ██      ██████   ██████   ███ ███  ██   ████
	*/
	$scope.empty_sizecharts_sizes_dropdown = function () {
		$rootScope.$broadcast('ams_do_uncheck_all', {
			name: 'ams_available_sizes'
		});
		$scope.available_sizes = [];
	};

	/*
	█████ █████ █████ █████ █████ █████ █████ █████ █████ █████ █████ █████ █████ █████ █████ █████
	█████ █████ █████ █████ █████ █████ █████ █████ █████ █████ █████ █████ █████ █████ █████ █████
	█████ █████ █████ █████ █████ █████ █████ █████ █████ █████ █████ █████ █████ █████ █████ █████
	*/

	/*
	 ██████ ██████  ███████  █████  ████████ ███████     ██████  ██████   ██████  ██████  ██    ██  ██████ ████████      ██████  ██████  ████████ ██  ██████  ███    ██
	██      ██   ██ ██      ██   ██    ██    ██          ██   ██ ██   ██ ██    ██ ██   ██ ██    ██ ██         ██        ██    ██ ██   ██    ██    ██ ██    ██ ████   ██
	██      ██████  █████   ███████    ██    █████       ██████  ██████  ██    ██ ██   ██ ██    ██ ██         ██        ██    ██ ██████     ██    ██ ██    ██ ██ ██  ██
	██      ██   ██ ██      ██   ██    ██    ██          ██      ██   ██ ██    ██ ██   ██ ██    ██ ██         ██        ██    ██ ██         ██    ██ ██    ██ ██  ██ ██
	 ██████ ██   ██ ███████ ██   ██    ██    ███████     ██      ██   ██  ██████  ██████   ██████   ██████    ██         ██████  ██         ██    ██  ██████  ██   ████
	*/
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

		$scope.build_price_stock_table();
	};

	/*
	██████  ███████ ███    ███  ██████  ██    ██ ███████     ██████  ██████   ██████  ██████  ██    ██  ██████ ████████      ██████  ██████  ████████ ██  ██████  ███    ██
	██   ██ ██      ████  ████ ██    ██ ██    ██ ██          ██   ██ ██   ██ ██    ██ ██   ██ ██    ██ ██         ██        ██    ██ ██   ██    ██    ██ ██    ██ ████   ██
	██████  █████   ██ ████ ██ ██    ██ ██    ██ █████       ██████  ██████  ██    ██ ██   ██ ██    ██ ██         ██        ██    ██ ██████     ██    ██ ██    ██ ██ ██  ██
	██   ██ ██      ██  ██  ██ ██    ██  ██  ██  ██          ██      ██   ██ ██    ██ ██   ██ ██    ██ ██         ██        ██    ██ ██         ██    ██ ██    ██ ██  ██ ██
	██   ██ ███████ ██      ██  ██████    ████   ███████     ██      ██   ██  ██████  ██████   ██████   ██████    ██         ██████  ██         ██    ██  ██████  ██   ████
	*/
	$scope.remove_product_option = function(option_id, index) {
		$scope.product.options[option_id].splice(index, 1);
		$scope.build_price_stock_table();
	};

	/*
	██████  ██    ██ ██ ██      ██████      ██████  ██████  ██  ██████ ███████     ███████ ████████  ██████   ██████ ██   ██     ████████  █████  ██████  ██      ███████
	██   ██ ██    ██ ██ ██      ██   ██     ██   ██ ██   ██ ██ ██      ██          ██         ██    ██    ██ ██      ██  ██         ██    ██   ██ ██   ██ ██      ██
	██████  ██    ██ ██ ██      ██   ██     ██████  ██████  ██ ██      █████       ███████    ██    ██    ██ ██      █████          ██    ███████ ██████  ██      █████
	██   ██ ██    ██ ██ ██      ██   ██     ██      ██   ██ ██ ██      ██               ██    ██    ██    ██ ██      ██  ██         ██    ██   ██ ██   ██ ██      ██
	██████   ██████  ██ ███████ ██████      ██      ██   ██ ██  ██████ ███████     ███████    ██     ██████   ██████ ██   ██        ██    ██   ██ ██████  ███████ ███████
	*/
	$scope.build_price_stock_table = function () {
		/*Data access failsafe*/
		$scope.original_product.options = $scope.original_product.options || {};
		$scope.product.sizechart.values = $scope.product.sizechart.values || [];
		$scope.original_product.sizechart.values = $scope.original_product.sizechart.values || [];
		/**/

		if ($scope.use_sizecharts === true &&
			($scope.product.sizechart.short_id === undefined ||
			$scope.product.sizechart.country === undefined)
		) {
			return;
		}

		/*
		 * Loop over all the selected tags and keep only the ones that are relevant
		 * for the price & stock table.
		 */
		$scope.tags_order_for_price_stock_table = [];
		$scope.tags_values_for_price_stock_table = {}; //Used to calculate the permutation of values

		if ($scope.use_sizecharts === true) {
			$scope.tags_order_for_price_stock_table.push("size");
			$scope.tags_values_for_price_stock_table["size"] = [];
			angular.forEach($scope.product.sizechart.values, function (value) {
				$scope.tags_values_for_price_stock_table["size"].push(value[0]);
			});
		}

		var same = true;
		var selected_tags_id = Object.keys($scope.product.options);
		for (var i = 0; i < selected_tags_id.length; i++) {
			var tag_id = selected_tags_id[i];
			var tag = $scope.getTag(tag_id);

			if (tag.stock_and_price === true) {
				// If the tag has no selected values, we just abort here.
				if ($scope.product.options[tag_id].length === 0) {
					$scope.product.price_stock = [];
					return;
				}

				$scope.tags_order_for_price_stock_table.push(tag_id);
				$scope.tags_values_for_price_stock_table[tag.short_id] = JSON.parse(JSON.stringify($scope.product.options[tag_id]));

				//Check if the currently selected tag and it's options match the original product
				if (same === true) {
					same = angular.equals($scope.product.options[tag_id], $scope.original_product.options[tag_id]);
				}
			}
		}


		if ($scope.use_sizecharts) {
			var selected_sizes = $scope.product.sizechart.values.map(function (v) { return v[0]; });
			var original_selected_sizes = $scope.original_product.sizechart.values.map(function (v) { return v[0]; });
			if (
				same === true &&
				$scope.product.sizechart.short_id === $scope.original_product.sizechart.short_id &&
				$scope.product.sizechart.country === $scope.original_product.sizechart.country &&
				angular.equals(selected_sizes, original_selected_sizes)
			) {
				$scope.product.price_stock = JSON.parse(JSON.stringify($scope.original_product.price_stock));
				return;
			}
		}

		var price_stock_table_combinations  = $scope.tags_order_for_price_stock_table.map(function (tag_id) {
			return $scope.tags_values_for_price_stock_table[tag_id];
		});
		var price_stock_table_options = $scope.permute(price_stock_table_combinations);

		$scope.product.price_stock = [];
		angular.forEach(price_stock_table_options, function (options) {
			var price_stock_row = {
				options: {},
				weight: 0,
				stock: 0,
				price: 0
			};
			for (var i = 0; i < $scope.tags_order_for_price_stock_table.length; i++) {
				var tag_id = $scope.tags_order_for_price_stock_table[i];
				price_stock_row.options[tag_id] = options[i];

				//TODO: Try to fill data from original product if all selected tags/values match in both of them
			}

			$scope.product.price_stock.push(price_stock_row);
		});
	};

	/*
	███████ ███    ███ ██████  ████████ ██    ██     ██████  ██████  ██  ██████ ███████     ███████ ████████  ██████   ██████ ██   ██     ████████  █████  ██████  ██      ███████
	██      ████  ████ ██   ██    ██     ██  ██      ██   ██ ██   ██ ██ ██      ██          ██         ██    ██    ██ ██      ██  ██         ██    ██   ██ ██   ██ ██      ██
	█████   ██ ████ ██ ██████     ██      ████       ██████  ██████  ██ ██      █████       ███████    ██    ██    ██ ██      █████          ██    ███████ ██████  ██      █████
	██      ██  ██  ██ ██         ██       ██        ██      ██   ██ ██ ██      ██               ██    ██    ██    ██ ██      ██  ██         ██    ██   ██ ██   ██ ██      ██
	███████ ██      ██ ██         ██       ██        ██      ██   ██ ██  ██████ ███████     ███████    ██     ██████   ██████ ██   ██        ██    ██   ██ ██████  ███████ ███████
	*/
	$scope.empty_price_stock_table = function () {
		$scope.product.price_stock = [];
	};

	/*
	███████  █████  ██    ██ ███████     ███████ ██ ███████ ███████  ██████ ██   ██  █████  ██████  ████████
	██      ██   ██ ██    ██ ██          ██      ██    ███  ██      ██      ██   ██ ██   ██ ██   ██    ██
	███████ ███████ ██    ██ █████       ███████ ██   ███   █████   ██      ███████ ███████ ██████     ██
	     ██ ██   ██  ██  ██  ██               ██ ██  ███    ██      ██      ██   ██ ██   ██ ██   ██    ██
	███████ ██   ██   ████   ███████     ███████ ██ ███████ ███████  ██████ ██   ██ ██   ██ ██   ██    ██
	*/
	$scope.save_sizechart = function() {
		toastr.info("Not implemented yet");
	};

	/*
	██████  ███████ ██      ███████ ████████ ███████     ███████ ██ ███████ ███████     ███████ ██████   ██████  ███    ███     ████████  █████  ██████  ██      ███████
	██   ██ ██      ██      ██         ██    ██          ██      ██    ███  ██          ██      ██   ██ ██    ██ ████  ████        ██    ██   ██ ██   ██ ██      ██
	██   ██ █████   ██      █████      ██    █████       ███████ ██   ███   █████       █████   ██████  ██    ██ ██ ████ ██        ██    ███████ ██████  ██      █████
	██   ██ ██      ██      ██         ██    ██               ██ ██  ███    ██          ██      ██   ██ ██    ██ ██  ██  ██        ██    ██   ██ ██   ██ ██      ██
	██████  ███████ ███████ ███████    ██    ███████     ███████ ██ ███████ ███████     ██      ██   ██  ██████  ██      ██        ██    ██   ██ ██████  ███████ ███████
	*/
	$scope.deleteSizeFromTable = function (row_index) {
		$scope.product.sizechart.values.splice(row_index, 1);
		$scope.build_sizecharts_sizes_dropdown();
		$scope.build_price_stock_table();
	};

	/*
	██ ███    ██ ███████ ███████ ██████  ████████     ███████ ██ ███████ ███████     ██ ███    ██     ████████  █████  ██████  ██      ███████
	██ ████   ██ ██      ██      ██   ██    ██        ██      ██    ███  ██          ██ ████   ██        ██    ██   ██ ██   ██ ██      ██
	██ ██ ██  ██ ███████ █████   ██████     ██        ███████ ██   ███   █████       ██ ██ ██  ██        ██    ███████ ██████  ██      █████
	██ ██  ██ ██      ██ ██      ██   ██    ██             ██ ██  ███    ██          ██ ██  ██ ██        ██    ██   ██ ██   ██ ██      ██
	██ ██   ████ ███████ ███████ ██   ██    ██        ███████ ██ ███████ ███████     ██ ██   ████        ██    ██   ██ ██████  ███████ ███████
	*/
	$scope.insertSizeInTable = function () {
		if ($scope.tmp_selected_sizechart_size === undefined) return;

		var sizechart = $scope.getSizechart($scope.product.sizechart.short_id);

		var row;
		var previous_sizes = [];
		var country_index = sizechart.countries.indexOf($scope.product.sizechart.country);
		for (var i = 0; i < sizechart.values.length; i++) {
			var value = sizechart.values[i];

			if (value[country_index] !== $scope.tmp_selected_sizechart_size) {
				previous_sizes.push(value[country_index]);
			} else {
				row = value.slice(sizechart.countries.length);
				row.unshift($scope.tmp_selected_sizechart_size);
				break;
			}
		}

		if (previous_sizes.length === 0) {
			$scope.product.sizechart.values.unshift(row);
		} else {
			var already_added_sizes = $scope.product.sizechart.values.map(function (v) {
				return v[0];
			});
			var previous_row = _.intersection(previous_sizes, already_added_sizes);

			for (var i = 0; i < $scope.product.sizechart.values.length; i++) {
				if ($scope.product.sizechart.values[i][0] === previous_row[previous_row.length - 1]) {
					$scope.product.sizechart.values.splice(i + 1, 0, row);
					break;
				}
			}
		}

		$scope.build_sizecharts_sizes_dropdown();
		$scope.build_price_stock_table();
	};

	/*
	██████  ██    ██ ██ ██      ██████      ███████ ██ ███████ ███████  ██████ ██   ██  █████  ██████  ████████     ████████  █████  ██████  ██      ███████
	██   ██ ██    ██ ██ ██      ██   ██     ██      ██    ███  ██      ██      ██   ██ ██   ██ ██   ██    ██           ██    ██   ██ ██   ██ ██      ██
	██████  ██    ██ ██ ██      ██   ██     ███████ ██   ███   █████   ██      ███████ ███████ ██████     ██           ██    ███████ ██████  ██      █████
	██   ██ ██    ██ ██ ██      ██   ██          ██ ██  ███    ██      ██      ██   ██ ██   ██ ██   ██    ██           ██    ██   ██ ██   ██ ██      ██
	██████   ██████  ██ ███████ ██████      ███████ ██ ███████ ███████  ██████ ██   ██ ██   ██ ██   ██    ██           ██    ██   ██ ██████  ███████ ███████
	*/
	$scope.build_sizechart_table = function () {
		if (
			$scope.product.sizechart.short_id === undefined ||
			$scope.product.sizechart.country === undefined
		) {
			return;
		}

		if (
			$scope.product.sizechart.short_id === $scope.original_product.sizechart.short_id &&
			$scope.product.sizechart.country === $scope.original_product.sizechart.country
		) {
			$scope.product.sizechart = JSON.parse(JSON.stringify($scope.original_product.sizechart));
			$scope.empty_sizecharts_sizes_dropdown();
		} else {
			delete $scope.product.sizechart.values;
			var sizechart = $scope.getSizechart($scope.product.sizechart.short_id);

			$scope.product.sizechart.metric_unit = sizechart.metric_unit;
			$scope.product.sizechart.columns = JSON.parse(JSON.stringify(sizechart.columns));

			var values = JSON.parse(JSON.stringify(sizechart.values));
			var country_index = sizechart.countries.indexOf($scope.product.sizechart.country);
			values = values.map(function (value) {
				var size = value[country_index];
				value = value.slice(sizechart.countries.length);
				value.unshift(size);
				return value;
			});
			$scope.product.sizechart.values = values;

			$scope.build_sizecharts_sizes_dropdown();
		}
	};

	/*
	███████ ███    ███ ██████  ████████ ██    ██     ███████ ██ ███████ ███████  ██████ ██   ██  █████  ██████  ████████     ████████  █████  ██████  ██      ███████
	██      ████  ████ ██   ██    ██     ██  ██      ██      ██    ███  ██      ██      ██   ██ ██   ██ ██   ██    ██           ██    ██   ██ ██   ██ ██      ██
	█████   ██ ████ ██ ██████     ██      ████       ███████ ██   ███   █████   ██      ███████ ███████ ██████     ██           ██    ███████ ██████  ██      █████
	██      ██  ██  ██ ██         ██       ██             ██ ██  ███    ██      ██      ██   ██ ██   ██ ██   ██    ██           ██    ██   ██ ██   ██ ██      ██
	███████ ██      ██ ██         ██       ██        ███████ ██ ███████ ███████  ██████ ██   ██ ██   ██ ██   ██    ██           ██    ██   ██ ██████  ███████ ███████
	*/
	$scope.empty_sizechart_table = function () {
		delete $scope.product.sizechart.metric_unit;
		delete $scope.product.sizechart.columns;
		delete $scope.product.sizechart.values;
	};

	/*
	███████  █████  ██    ██ ███████
	██      ██   ██ ██    ██ ██
	███████ ███████ ██    ██ █████
	     ██ ██   ██  ██  ██  ██
	███████ ██   ██   ████   ███████
	*/
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
				data: {
					'file': photo.blob,
					'data': JSON.stringify({
						'name': photo.name,
						'tags': photo.tags
					})
				}
			}).then(function(resp) {
				photos[index] = angular.copy(resp.data.media.photos.pop());
				toastr.success("Uploaded successfully product photo", resp.config.data.file.name);
			}, function(err) {
				photos[index].not_uploaded = true;
				toastr.error("Error while uploading product photo", err)
			}, function(e) {
				var progressPercentage = parseInt(100.0 * e.loaded / e.total);
				photo.progress = progressPercentage;
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

	/*
	██ ███    ██ ██ ████████
	██ ████   ██ ██    ██
	██ ██ ██  ██ ██    ██
	██ ██  ██ ██ ██    ██
	██ ██   ████ ██    ██
	*/

	/*
	 * PHP's arrays have the same notation for arrays and objects and (to|from)JSON
	 * get's confused. That's why we must help it here.
	 */
	global.arrayToObject($scope.product, ["name", "slug", "description", "media", "options", "madetoorder", "sizechart", "bespoke", "preorder", "returns", "warranty"]);

	//Required for the name and description dropdowns
	$scope.langs = [];
	$scope.languages = "";
	angular.forEach(_langs, function(v, k) {
		var sw = $scope.product.name.hasOwnProperty(k);
		$scope.langs.push({ "key": k, "value": v, "checked": sw});
	});

	//Required for the categories dropdown
	$scope.categories = $category_util.create_tree(_categories);

	/*
	██████  ███████  █████   ██████ ████████     ████████  ██████       ██████ ██   ██  █████  ███    ██  ██████  ███████ ███████
	██   ██ ██      ██   ██ ██         ██           ██    ██    ██     ██      ██   ██ ██   ██ ████   ██ ██       ██      ██
	██████  █████   ███████ ██         ██           ██    ██    ██     ██      ███████ ███████ ██ ██  ██ ██   ███ █████   ███████
	██   ██ ██      ██   ██ ██         ██           ██    ██    ██     ██      ██   ██ ██   ██ ██  ██ ██ ██    ██ ██           ██
	██   ██ ███████ ██   ██  ██████    ██           ██     ██████       ██████ ██   ██ ██   ██ ██   ████  ██████  ███████ ███████
	*/
	$scope.$on('ams_output_model_change', function (event, args) {

		switch (args.name) {
			case "categories":
				//If there are no selected categories we don't have any further work to do.
				if ($scope.product.categories.length === 0) break;

				//Else ... build the tags
				$scope.build_tags();

				//... and decide if we should show sizecharts
				if ($scope.has_sizecharts()) {
					$scope.use_sizecharts = true;

					//... and build the sizecharts dropdown if we should show sizecharts!
					$scope.build_sizecharts_dropdown();
				} else {
					$scope.use_sizecharts = false;

					$scope.empty_sizecharts_dropdown();
				}

				//... or prints
				if ($scope.has_prints()) {
					$scope.use_prints = true;
				} else {
					$scope.use_prints = false;
				}
				break;

			case "ams_sizechart":
				//If there is no selected sizechart or if there was but got unselected
				if ($scope.product.sizechart.short_id === undefined) {
					$scope.empty_sizecharts_countries_dropdown();
					$scope.empty_sizechart_table();
					$scope.empty_price_stock_table();
				} else {
					$scope.build_sizecharts_countries_dropdown();
					$scope.build_sizechart_table();
					$scope.build_price_stock_table();
				}
				break;

			case "ams_sizechart_country":
				if ($scope.product.sizechart.country === undefined) {
					$scope.empty_sizecharts_sizes_dropdown();
					$scope.empty_sizechart_table();
					$scope.empty_price_stock_table();
				} else {
					$scope.build_sizecharts_sizes_dropdown();
					$scope.build_sizechart_table();
					$scope.build_price_stock_table();
				}
				break;

			case String(args.name.match(/^ams_tag_value.*/)):
				$rootScope.$broadcast('ams_open', {
					name: args.name
				});
				$scope.build_price_stock_table();
				break;

			default:

		}

	});

	$scope.$on('ams_input_model_change', function (event, args) {
		switch (args.name) {
			//TODO: case "ams_available_sizes" -> build_price_stock_table
			case "ams_available_sizes":
				//$scope.build_sizechart_table();

				//console.log("Building price from ams_available_sizes event");
				//$scope.build_price_stock_table();
				break;

			default:

		}
	});

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

	/*
	██ ███    ██ ██ ████████     ██   ██  █████   ██████ ██   ██
	██ ████   ██ ██    ██        ██   ██ ██   ██ ██      ██  ██
	██ ██ ██  ██ ██    ██        ███████ ███████ ██      █████
	██ ██  ██ ██ ██    ██        ██   ██ ██   ██ ██      ██  ██
	██ ██   ████ ██    ██        ██   ██ ██   ██  ██████ ██   ██
	*/

	/*
	 * This is required because AMS won't emit an output_model_change event on initially loaded data
	 */
	$scope.$broadcast("ams_output_model_change", {
		name: "categories"
	});
	$scope.$broadcast("ams_output_model_change", {
		name: "ams_sizechart"
	});


}]);
