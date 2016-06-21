var todevise = angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'ngJsTree', 'global-admin', 'global-desktop', 'api']);

todevise.controller('faqsCtrl', ["$scope", "$faqs", "$category_util", "$location", "toastr", "$uibModal", function($scope, $faqs, $category_util, $location, toastr, $uibModal) {

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

		$scope.load_faqs();
	};

	$scope.load_faqs = function() {
		$faqs.get().then(function(_faqs) {

			//Empty the data holder (if there is anything)
			$scope.treeData.splice(0, $scope.treeData.length);

			console.log(_faqs);
			//Fill the new values
			angular.forEach(_faqs, function(faqs_group, key){
				//console.log(faqs_group.title[_lang]);
				short_id = faqs_group.short_id;
				$scope.treeInstance.jstree(true).add_action(short_id, {
					"id": "action_add",
					"class": "action_add pull-right",
					"text": "",
					"after": true,
					"selector": "a",
					"event": "click",
					"callback": $scope.edit_faq
				});
				//a group
				$scope.treeData.push(
					{
						id: short_id,
						parent: '#',
						text: faqs_group.title[_lang] || "",
						icon: "none"
					}
				);

				if(faqs_group.faqs.length > 0){
					//faqs inside group
					angular.forEach(faqs_group.faqs, function (faqs, key) {
						console.log(faqs.question[_lang]);
						$scope.treeData.push(
							{
								id: short_id+'_'+key,
								short_id: key,
								parent: short_id,
								text: faqs.question[_lang] || "",
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
			templateUrl: 'template/modal/faqs/create_new.html',
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

			$faqs.modify("POST", tmp_node).then(function(faqs_group) {
				toastr.success("Category created!");
				console.log("debug id:");
				console.log(faqs_group);
				$scope.treeData.push(
					{
						id: faqs_group.short_id,
						parent: '#',
						text: faqs_group.title[_lang] || "",
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

	$scope.edit_faq = function(node_id, node, action_id, action_el) {
		//$scope.viewtoggle = ! $scope.viewtoggle;
		//TODO: Get this url from Yii url generator
		document.location.href = "/admin/faq/"+node.id+"/";
	};


	$scope.create_sub = function(node_id, node, action_id, action_el) {
		var path = "/";
		if (node !== undefined) {
			path += $scope.treeInstance.jstree(true).get_path(node, "/", true) + "/";
		}

		$faqs.modify("POST", tmp_node).then(function(faqs_group) {
			toastr.success("Category created!");
			console.log("debug id:");
			console.log(faqs_group);
			$scope.treeData.push(
				{
					id: faqs_group.short_id,
					parent: '#',
					text: faqs_group.title[_lang] || "",
					icon: "glyphicon glyphicon-menu-hamburger"
				}
			 );
		}, function(err) {
			toastr.error("Couldn't create category!", err);
		});
	};

	$scope.edit_sub = function (node_id, node, action_id, action_el) {
		var res = node.id.split("_");
		document.location.href = "/admin/faq/"+res[0]+"/"+res[1]+"/";

	}

	$scope.rename = function (node_id, node, action_id, action_el) {

		if(node.parent != '#'){
			return $scope.edit_sub(node_id, node, action_id, action_el);
		}

		$faqs.get({
			"short_id": node.id
		}).then(function(_faq) {
			if(_faq.length !== 1) {
				toastr.error("Unexpected category details!");
				return;
			} else {
				_faq = _faq.pop();
			}

			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/faqs/edit.html',
				controller: 'editCtrl',
				resolve: {
					data: function () {
						return {
							langs: _langs,
							faq: _faq
						};
					}
				}
			});

			modalInstance.result.then(function(data) {
				$faqs.modify("POST", data.category).then(function() {
					toastr.success("Faq modified!");

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
			toastr.error("Couldn't get faqs details!", err);
		});
	};

	$scope.move = function (e, node) {
		node.node.original.parent = node.node.parent;
		var tmp_node = $category_util.nodeToCategory(node.node.original, $scope);
		$faqs.modify("post", tmp_node).then(function(category) {
			$scope.load_faqs();
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
							text: "Faqs group will be removed"
						};
					}
				}
			});

			modalInstance.result.then(function() {
				$faqs.modify("delete", category).then(function (category) {
					$scope.load_faqs();
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
