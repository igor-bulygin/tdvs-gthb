(function () {
	"use strict";

	function categoriesCtrl($category, $category_util, toastr, $uibModal, treeService) {
		var vm = this;

		vm.treeData = [];

		vm.treeConfig = treeService.treeDefaultConfig("todevise_categories_jstree", vm);

		vm.load_categories = function () {
			$category.get().then(function (_categories) {

				//Sort data before feeding it to jsTree
				_categories = $category_util.sort(_categories);

				//Empty the data holder (if there is anything)
				vm.treeData.splice(0, vm.treeData.length);

				//Fill the new values
				angular.forEach(_categories, function (category, key) {
					vm.treeData.push($category_util.categoryToNode(category));
				});

				toastr.success("Categories loaded!");
			}, function (err) {
				toastr.error("Couldn't load categories!", err);
			});
		};

		vm.readyCB = function () {
			vm.treeInstance.jstree(true).add_action("all", {
				id: "action_add",
				class: "action_add pull-right",
				text: "",
				after: true,
				selector: "a",
				event: "click",
				callback: vm.create
			});


			vm.treeInstance.jstree(true).add_action("all", {
				id: "action_rename",
				class: "action_rename pull-right",
				text: "",
				after: true,
				selector: "a",
				event: "click",
				callback: vm.rename
			});

			vm.treeInstance.jstree(true).add_action("all", {
				id: "action_remove",
				class: "action_remove pull-right",
				text: "",
				after: true,
				selector: "a",
				event: "click",
				callback: vm.remove
			});

			vm.load_categories();

		}

		vm.search = function () {
			vm.treeInstance.jstree(true).search(vm.searchModel);
		}

		vm.open_all = function () {
			vm.treeInstance.jstree(true).open_all();
		}

		vm.close_all = function () {
			vm.treeInstance.jstree(true).close_all();
		}

		vm.restoreState = _.debounce(function () {
			vm.treeInstance.jstree(true).set_state(angular.copy(vm.tree_state));
		}, 100);

		vm.create = function (node_id, node, action_id, action_el) {
			var path = "/";
			if (node !== undefined) {
				path += vm.treeInstance.jstree(true).get_path(node, "/", true) + "/";
			}

			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/category/create_new.html',
				controller: create_newCtrl,
				controllerAs: "create_newCtrl",
				resolve: {
					data: function () {
						return {
							langs: _langs
						};
					}
				}
			});

			modalInstance.result.then(function (data) {
				var tmp_node = $category_util.newCategory(path, data.langs, data.slug, data.sizecharts, data.prints, data.header_position);

				$category.modify("POST", tmp_node).then(function (category) {
					toastr.success("Category created!");
					vm.treeData.push($category_util.categoryToNode(category));
				}, function (err) {
					toastr.error("Couldn't create category!", err);
				});
			}, function () {
				//Cancel
			});
		};

		vm.rename = function (node_id, node, action_id, action_el) {
			$category.get({
				short_id: node.id
			}).then(function (_category) {
				if (_category.length !== 1) {
					toastr.error("Unexpected category details!");
					return;
				} else {
					_category = _category.pop();
				}

				var modalInstance = $uibModal.open({
					templateUrl: 'template/modal/category/edit.html',
					controller: editCtrl,
					controllerAs: 'editCtrl',
					resolve: {
						data: function () {
							return {
								langs: _langs,
								category: _category
							};
						}
					}
				});

				modalInstance.result.then(function (data) {
					$category.modify("POST", data.category).then(function () {
						toastr.success("Category modified!");

						var _node = $category_util.categoryToNode(data.category);
						var _current = _.findWhere(vm.treeData, {
							id: data.category.short_id
						});
						angular.merge(_current, _node);
					}, function (err) {
						toastr.error("Couldn't modify category!", err);
					});
				}, function () {
					//Cancel
				});
			}, function (err) {
				toastr.error("Couldn't get category details!", err);
			});
		};

		vm.move = function (e, node) {
			node.node.original.parent = node.node.parent;
			var tmp_node = $category_util.nodeToCategory(node.node.original, vm);
			$category.modify("post", tmp_node).then(function (category) {
				vm.load_categories();
				toastr.success("Category moved!");
			}, function (err) {
				toastr.error("Couldn't move category!", err);
			});
		};

		vm.remove = function (node_id, node, action_id, action_el) {
			var category = vm.treeInstance.jstree(true).get_node(node.id);
			category = $category_util.nodeToCategory(category.original, vm);

			//Check for sub-categories that depend on this one so we can remove those too
			$category_util.subCategories(category).then(function (subcategories) {
				var modalInstance = $uibModal.open({
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

				modalInstance.result.then(function () {
					$category.modify("delete", category).then(function (category) {
						vm.load_categories();
						toastr.success("Category deleted!");
					}, function (err) {
						toastr.error("Couldn't remove category!", err);
					});

				}, function () {
					//Cancel
				});

			}, function (err) {
				toastr.error("Couldn't check dependencies!", err);
			});
		};

	}

	function create_newCtrl($uibModalInstance, data) {
		var vm = this;

		vm.data = data;
		vm.langs = {};
		vm.slug = "";
		vm.sizecharts = false;
		vm.prints = false;

		vm.ok = function () {
			$uibModalInstance.close({
				langs: vm.langs,
				slug: vm.slug,
				sizecharts: vm.sizecharts,
				prints: vm.prints,
				header_position: vm.header_position
			});
		};

		vm.cancel = function () {
			$uibModalInstance.dismiss();
		};
	}

	function editCtrl($uibModalInstance, data) {
		var vm = this;

		vm.data = data;

		vm.ok = function () {
			$uibModalInstance.close({
				category: vm.data.category
			});
		};

		vm.cancel = function () {
			$uibModalInstance.dismiss();
		}
	}

	angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'ngJsTree', 'global-admin', 'global-desktop', 'api', 'util'])
		.controller('categoriesCtrl', categoriesCtrl)
		.controller('create_newCtrl', create_newCtrl)
		.controller('editCtrl', editCtrl);

}());