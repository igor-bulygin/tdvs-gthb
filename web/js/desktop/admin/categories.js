var todevise = angular.module('todevise', ['ui.bootstrap', 'ngJsTree', 'global-admin', 'global-desktop', 'api']);

todevise.controller('categoriesCtrl', ["$scope", "$category", "$category_util", "toastr", "$modal", function($scope, $category, $category_util, toastr, $modal) {

	$scope.treeData = [];

	$scope.treeConfig = {
		core : {
			check_callback: true,
			themes: {
				name: "default-dark"
			}
		},
		state : {
			key : "todevise_categories_jstree",
			filter: function(state) {
				$scope.tree_state = {};
				angular.copy(state, $scope.tree_state);
			}
		},
		dnd: {
			touch: "selected"
		},
		search: {
			fuzzy: true,
			case_insensitive: true,
			show_only_matches : true
		},
		plugins: ["state", "dnd", "search", "sort", "wholerow", "actions", "types"]
	};

	$scope.readyCB = function () {
		$scope.treeInstance.jstree(true).add_action("all", {
			"id": "action_add",
			"class": "action_add pull-right",
			"text": "",
			"after": true,
			"selector": "a",
			"event": "click",
			"callback": $scope.create
		});

		$scope.treeInstance.jstree(true).add_action("all", {
			"id": "action_rename",
			"class": "action_rename pull-right",
			"text": "",
			"after": true,
			"selector": "a",
			"event": "click",
			"callback": $scope.rename
		});

		$scope.treeInstance.jstree(true).add_action("all", {
			"id": "action_remove",
			"class": "action_remove pull-right",
			"text": "",
			"after": true,
			"selector": "a",
			"event": "click",
			"callback": $scope.remove
		});

		$scope.load_categories();
	};

	$scope.load_categories = function() {
		$category.get().then(function(_categories) {

			//Sort data before feeding it to jsTree
			_categories = $category_util.sort(_categories);

			//Empty the data holder (if there is anything)
			$scope.treeData.splice(0, $scope.treeData.length);

			//Fill the new values
			angular.forEach(_categories, function (category, key) {
				$scope.treeData.push( $category_util.categoryToNode(category) );
			});
			toastr.success("Categories loaded!");
		}, function(err) {
			toastr.error("Couldn't load categories!", err);
		});
	};

	$scope.search = function() {
		$scope.treeInstance.jstree(true).search($scope.searchModel);
	};

	$scope.open_all = function() {
		$scope.treeInstance.jstree(true).open_all();
	};

	$scope.close_all = function() {
		$scope.treeInstance.jstree(true).close_all();
	};

	$scope.restoreState = _.debounce(function() {
		$scope.treeInstance.jstree(true).set_state(angular.copy($scope.tree_state));
	}, 100);

	$scope.create = function(node_id, node, action_id, action_el) {
		var path = "/";
		if (node !== undefined) {
			path += $scope.treeInstance.jstree(true).get_path(node, "/", true) + "/";
		}

		var modalInstance = $modal.open({
			templateUrl: 'template/modal/category/create_new.html',
			controller: 'create_newCtrl',
			resolve: {
				data: function () {
					return {
						langs: _langs
					};
				}
			}
		});

		modalInstance.result.then(function(data) {
			var tmp_node = $category_util.newCategory(path, data.langs, data.slug, data.sizecharts, data.prints);

			$category.modify("POST", tmp_node).then(function(category) {
				toastr.success("Category created!");
				$scope.treeData.push( $category_util.categoryToNode(category) );
			}, function(err) {
				toastr.error("Couldn't create category!", err);
			});
		}, function () {
			//Cancel
		});
	};

	$scope.rename = function (node_id, node, action_id, action_el) {

		$category.get({
			"short_id": node.id
		}).then(function(_category) {
			if(_category.length !== 1) {
				toastr.error("Unexpected category details!");
				return;
			} else {
				_category = _category.pop();
			}

			var modalInstance = $modal.open({
				templateUrl: 'template/modal/category/edit.html',
				controller: 'editCtrl',
				resolve: {
					data: function () {
						return {
							langs: _langs,
							category: _category
						};
					}
				}
			});

			modalInstance.result.then(function(data) {
				$category.modify("POST", data.category).then(function() {
					toastr.success("Category modified!");

					var _node = $category_util.categoryToNode(data.category);
					var _current = _.findWhere($scope.treeData, {id: data.category.short_id});
					angular.merge(_current, _node);
				}, function(err) {
					toastr.error("Couldn't modify category!", err);
				});

			}, function () {
				//Cancel
			});
		}, function(err) {
			toastr.error("Couldn't get category details!", err);
		});
	};

	$scope.move = function (e, node) {
		node.node.original.parent = node.node.parent;
		var tmp_node = $category_util.nodeToCategory(node.node.original, $scope);
		$category.modify("post", tmp_node).then(function(category) {
			$scope.load_categories();
			toastr.success("Category moved!");
		}, function(err) {
			toastr.error("Couldn't move category!", err);
		});
	};

	$scope.remove = function (node_id, node, action_id, action_el) {
		var category = $scope.treeInstance.jstree(true).get_node(node.id);
		category = $category_util.nodeToCategory(category.original, $scope);

		//Check for sub-categories that depend on this one so we can remove those too
		$category_util.subCategories(category).then(function(subcategories) {
			var modalInstance = $modal.open({
				templateUrl: 'template/modal/confirm.html',
				controller: 'confirmCtrl',
				resolve: {
					data: function () {
						return {
							title: "Are you sure?",
							text: _.size(subcategories) + " additional categories will be removed"
						};
					}
				}
			});

			modalInstance.result.then(function() {
				$category.modify("delete", category).then(function (category) {
					$scope.load_categories();
					toastr.success("Category deleted!");
				}, function (err) {
					toastr.error("Couldn't remove category!", err);
				});

			}, function() {
				//Cancel
			});

		}, function(err) {
			toastr.error("Couldn't check dependencies!", err);
		});
	};

}]);

todevise.controller("create_newCtrl", function($scope, $modalInstance, data) {
	$scope.data = data;
	$scope.langs = {};
	$scope.slug = "";
	$scope.sizecharts = false;
	$scope.prints = false;

	$scope.ok = function() {
		$modalInstance.close({
			"langs": $scope.langs,
			"slug": $scope.slug,
			"sizecharts": $scope.sizecharts,
			"prints": $scope.prints
		});
	};

	$scope.cancel =  function() {
		$modalInstance.dismiss();
	};
});


todevise.controller("editCtrl", function($scope, $modalInstance, data) {
	$scope.data = data;

	$scope.ok = function() {
		$modalInstance.close({
			"category": $scope.data.category
		});
	};

	$scope.cancel =  function() {
		$modalInstance.dismiss();
	};
});
