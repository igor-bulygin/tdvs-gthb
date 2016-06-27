var todevise = angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'ngJsTree', 'global-admin', 'global-desktop', 'api']);

todevise.controller('termsCtrl', ["$scope", "$terms", "$category_util", "$location", "toastr", "$uibModal", function($scope, $terms, $category_util, $location, toastr, $uibModal) {

	$scope.treeData = [];
	$scope.langs = _langs;

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
			fuzzy: false,
			case_insensitive: true,
			show_only_matches: true,
			search_only_leaves: false
		},
		plugins: ["state", "dnd", "search", "sort", "wholerow", "actions", "types"]
	};

	$scope.readyCB = function () {
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

		$scope.load_terms();
	};

	$scope.load_terms = function() {
		$terms.get().then(function(_terms) {

			//Empty the data holder (if there is anything)
			$scope.treeData.splice(0, $scope.treeData.length);

			console.log(_terms);
			//Fill the new values
			angular.forEach(_terms, function(terms_group, key){
				//console.log(terms_group.title[_lang]);
				short_id = terms_group.short_id;
				$scope.treeInstance.jstree(true).add_action(short_id, {
					"id": "action_add",
					"class": "action_add pull-right",
					"text": "",
					"after": true,
					"selector": "a",
					"event": "click",
					"callback": $scope.edit_term
				});
				//a group
				$scope.treeData.push(
					{
						id: short_id,
						parent: '#',
						text: terms_group.title[_lang] || "",
						icon: "none"
					}
				);

				if(terms_group.terms.length > 0){
					//terms inside group
					angular.forEach(terms_group.terms, function (terms, key) {
						console.log(terms.question[_lang]);
						$scope.treeData.push(
							{
								id: short_id+'_'+key,
								short_id: key,
								parent: short_id,
								text: terms.question[_lang] || "",
								icon: "none"
							}
						);
					});
				}


			});
			console.log($scope.treeData);
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

		var modalInstance = $uibModal.open({
			templateUrl: 'template/modal/terms/create_new.html',
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

			var tmp_node = {
				short_id: "new",
				title: data.langs
			};

			$terms.modify("POST", tmp_node).then(function(terms_group) {
				toastr.success("Category created!");
				console.log("debug id:");
				console.log(terms_group);
				$scope.treeData.push(
					{
						id: terms_group.short_id,
						parent: '#',
						text: terms_group.title[_lang] || "",
						icon: "none"
					}
				 );
			}, function(err) {
				toastr.error("Couldn't create category!", err);
			});
		}, function () {
			//Cancel
		});
	};

	$scope.edit_term = function(node_id, node, action_id, action_el) {
		//$scope.viewtoggle = ! $scope.viewtoggle;
		//TODO: Get this url from Yii url generator
		document.location.href = "/admin/term/"+node.id+"/";
	};


	$scope.create_sub = function(node_id, node, action_id, action_el) {
		var path = "/";
		if (node !== undefined) {
			path += $scope.treeInstance.jstree(true).get_path(node, "/", true) + "/";
		}

		$terms.modify("POST", tmp_node).then(function(terms_group) {
			toastr.success("Category created!");
			console.log("debug id:");
			console.log(terms_group);
			$scope.treeData.push(
				{
					id: terms_group.short_id,
					parent: '#',
					text: terms_group.title[_lang] || "",
					icon: "glyphicon glyphicon-menu-hamburger"
				}
			 );
		}, function(err) {
			toastr.error("Couldn't create category!", err);
		});
	};

	$scope.edit_sub = function (node_id, node, action_id, action_el) {
		var res = node.id.split("_");
		document.location.href = "/admin/term/"+res[0]+"/"+res[1]+"/";

	}

	$scope.rename = function (node_id, node, action_id, action_el) {

		if(node.parent != '#'){
			return $scope.edit_sub(node_id, node, action_id, action_el);
		}

		$terms.get({
			"short_id": node.id
		}).then(function(_term) {
			if(_term.length !== 1) {
				toastr.error("Unexpected category details!");
				return;
			} else {
				_term = _term.pop();
			}

			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/terms/edit.html',
				controller: 'editCtrl',
				resolve: {
					data: function () {
						return {
							langs: _langs,
							term: _term
						};
					}
				}
			});

			modalInstance.result.then(function(data) {
				$terms.modify("POST", data.category).then(function() {
					toastr.success("term modified!");

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
			toastr.error("Couldn't get terms details!", err);
		});
	};

	$scope.move = function (e, node) {
		node.node.original.parent = node.node.parent;
		var tmp_node = $category_util.nodeToCategory(node.node.original, $scope);
		$terms.modify("post", tmp_node).then(function(category) {
			$scope.load_terms();
			toastr.success("Category moved!");
		}, function(err) {
			toastr.error("Couldn't move category!", err);
		});
	};

	$scope.remove = function (node_id, node, action_id, action_el) {
		var category = $scope.treeInstance.jstree(true).get_node(node.id);
		category = $category_util.nodeToCategory(category.original, $scope);

		//Check for sub-categories that depend on this one so we can remove those too
		// $category_util.subCategories(category).then(function(subcategories) {
			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/confirm.html',
				controller: 'confirmCtrl',
				resolve: {
					data: function () {
						return {
							title: "Are you sure?",
							text: "terms group will be removed"
						};
					}
				}
			});

			modalInstance.result.then(function() {
				$terms.modify("delete", category).then(function (category) {
					$scope.load_terms();
					toastr.success("Category deleted!");
				}, function (err) {
					toastr.error("Couldn't remove category!", err);
				});

			}, function() {
				//Cancel
			});

		// }, function(err) {
		// 	toastr.error("Couldn't check dependencies!", err);
		// });
	};

}]);

todevise.controller("create_newCtrl", function($scope, $uibModalInstance, data) {
	$scope.data = data;
	$scope.langs = {};
	$scope.slug = "";
	$scope.sizecharts = false;
	$scope.prints = false;

	$scope.ok = function() {
		$uibModalInstance.close({
			"langs": $scope.langs,
			"slug": $scope.slug,
			"sizecharts": $scope.sizecharts,
			"prints": $scope.prints
		});
	};

	$scope.cancel =  function() {
		$uibModalInstance.dismiss();
	};
});


todevise.controller("editCtrl", function($scope, $uibModalInstance, data) {
	$scope.data = data;

	$scope.ok = function() {
		$uibModalInstance.close({
			"category": $scope.data.category
		});
	};

	$scope.cancel =  function() {
		$uibModalInstance.dismiss();
	};
});
