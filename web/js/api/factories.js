var api = angular.module('api');

api.factory("$tag_util", function() {
	var utils = {};

	utils.newTag = function(description, langs) {
		return {
			short_id: "new",
			description: {
				"en-US": description
			},
			name: langs,
			type: 0,
			n_options: 1,
			options: {}
		};
	};

	utils.newDropdownOption = function() {
		return {
			text: {},
			value: null
		};
	};

	utils.newFreetextOption = function() {
		return {
			text: {},
			type: null,
			metric_unit: null
		};
	};

	return utils;
});

api.factory("$deviser_util", function() {
	var utils = {};

	utils.newDeviser = function(type, name, surnames, email, country_code, slug) {
		return {
			short_id: "new",
			slug: slug,
			type: type,
			categories: [],
			collections: [],
			personal_info: {
				name: name,
				surnames: surnames,
				country: country_code
			},
			media: {},
			credentials: {
				email: email
			}
		};
	};

	return utils;
});

api.factory("$sizechart_util", function() {
	var utils = {};

	utils.newSizeChart = function(langs) {
		return {
			short_id: "new",
			name: langs,
			countries: [],
			columns: [],
			values: []
		};
	};

	return utils;
});

api.factory("$category_util", function($q, $category) {
	var utils = {};

	utils.nodeToCategory = function(node, $scope) {
		var path = "/";
		if (node.parent !== "#") {
			var tmp_path = $scope.treeInstance.jstree(true).get_path(node.id, false, true);
			tmp_path.pop();
			path += tmp_path.join("/") + "/";
		}

		var tmp_node = {
			short_id: node.id,
			path: path,
			name: {}
		};
		tmp_node.name[_lang] = node.text;
		return tmp_node;
	};

	utils.categoryToNode = function(category) {
		var parent_id = category.path === "/" ? "#" : category.path.split("/");
		return {
			id: category.short_id,
			parent: typeof parent_id === "object" ? parent_id[parent_id.length - 2] : parent_id,
			text: category.name[_lang] || ""
		};
	};

	utils.subCategories = function(category) {
		var deferred = $q.defer();

		$category.get().then(function(categories) {
			var path = _.findWhere(categories, {
					short_id: category.short_id
				}).path + category.short_id + "/";

			var subcategories = _.filter(categories, function(category) {
				return category.path.startsWith(path);
			});

			deferred.resolve(subcategories);
		}, function(err) {
			deferred.reject(err);
		});

		return deferred.promise;
	};

	utils.newCategory = function(path) {
		return {
			short_id: "new",
			path: path,
			name: {
				"en-US": "New category"
			}
		};
	};

	utils.sort = function(categories) {
		categories.sort(function(a, b) {
			return a.path.length > b.path.length;
		});
	};

	utils.create_tree = function(categories) {
		var _categories = [];

		angular.forEach(angular.copy(categories), function(_category) {
			if (_category.path === "/") {
				_categories.push(_category);
			} else {
				var _path = _category.path.split("/");
				_path.pop();
				var _parent = _path.pop();

				jsonpath.apply(_categories, "$..[?(@.short_id=='" + _parent + "')]", function(obj) {
					if(!obj.hasOwnProperty("sub")) {
						obj.sub = [];
					}
					obj.sub.push(_category);
					return obj;
				});
			}
		});

		return _categories;
	};

	return utils;
});
