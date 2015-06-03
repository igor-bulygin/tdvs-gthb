var api = angular.module('api');

api.factory("$category_util", function($q, $category, CONSTS) {
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
		tmp_node.name[CONSTS.current_lang] = node.text;
		return tmp_node;
	};

	utils.categoryToNode = function(category) {
		var parent_id = category.path === "/" ? "#" : category.path.split("/");
		return {
			id: category.short_id,
			parent: typeof parent_id === "object" ? parent_id[parent_id.length - 2] : parent_id,
			text: category.name[CONSTS.current_lang] || ""
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

	return utils;
});