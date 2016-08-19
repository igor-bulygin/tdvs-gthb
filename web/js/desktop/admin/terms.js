(function () {
	"use strict";

	function controller($terms, $category_util, $location, toastr, $uibModal, treeService) {
		var vm = this;
		vm.treeData = [];
		vm.langs = _langs;

		vm.treeConfig = treeService.treeDefaultConfig("todevise_categories_jstree", vm);

		vm.readyCB = readyCB;
		vm.load_terms = load_terms;
		vm.search = search;
		vm.open_all = open_all;
		vm.close_all = close_all;
		vm.restoreState = _.debounce(restoreState, 100);
		vm.create = create;
		vm.edit_term = edit_term;
		vm.create_sub = create_sub;
		vm.edit_sub = edit_sub;
		vm.rename = rename;
		vm.move = move;
		vm.remove = remove;

		function readyCB() {
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

			vm.load_terms();
		}

		function load_terms() {
			$terms.get().then(function (_terms) {

				//Empty the data holder (if there is anything)
				vm.treeData.splice(0, vm.treeData.length);

				console.log(_terms);
				//Fill the new values
				angular.forEach(_terms, function (terms_group, key) {
					//console.log(terms_group.title[_lang]);
					var short_id = terms_group.short_id;
					vm.treeInstance.jstree(true).add_action(short_id, {
						id: "action_add",
						class: "action_add pull-right",
						text: "",
						after: true,
						selector: "a",
						event: "click",
						callback: vm.edit_term
					});
					//a group
					vm.treeData.push({
						id: short_id,
						parent: '#',
						text: terms_group.title[_lang] || "",
						icon: "none"
					});

					if (terms_group.terms.length > 0) {
						//terms inside group
						angular.forEach(terms_group.terms, function (terms, key) {
							console.log(terms.question[_lang]);
							vm.treeData.push({
								id: short_id + '_' + key,
								short_id: key,
								parent: short_id,
								text: terms.question[_lang] || "",
								icon: "none"
							});
						});
					}


				});
				console.log(vm.treeData);
				toastr.success("Categories loaded!");
			}, function (err) {
				toastr.error("Couldn't load categories!", err);
			});
		}

		function search() {
			vm.treeInstance.jstree(true).search(vm.searchModel);
		}

		function open_all() {
			vm.treeInstance.jstree(true).open_all();
		}

		function close_all() {
			vm.treeInstance.jstree(true).close_all();

		}

		function restoreState() {
			vm.treeInstance.jstree(true).set_state(angular.copy(vm.tree_state));
		}

		function create(node_id, node, action_id, action_el) {
			var path = "/";
			if (node !== undefined) {
				path += vm.treeInstance.jstree(true).get_path(node, "/", true) + "/";
			}

			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/terms/create_new.html',
				controller: create_newCtrl,
				controllerAs: 'create_newCtrl',
				resolve: {
					data: function () {
						return {
							langs: _langs
						};
					}
				}
			});

			modalInstance.result.then(function (data) {

				var tmp_node = {
					short_id: "new",
					title: data.langs
				};

				$terms.modify("POST", tmp_node).then(function (terms_group) {
					toastr.success("Category created!");
					console.log("debug id:");
					console.log(terms_group);
					vm.treeData.push({
						id: terms_group.short_id,
						parent: '#',
						text: terms_group.title[_lang] || "",
						icon: "none"
					});
				}, function (err) {
					toastr.error("Couldn't create category!", err);
				});
			}, function () {
				//Cancel
			});

		}

		function edit_term(node_id, node, action_id, action_el) {
			//vm.viewtoggle = ! vm.viewtoggle;
			//TODO: Get this url from Yii url generator
			document.location.href = "/admin/term/" + node.id + "/";
		}

		function create_sub(node_id, node, action_id, action_el) {
			var path = "/";
			if (node !== undefined) {
				path += vm.treeInstance.jstree(true).get_path(node, "/", true) + "/";
			}

			$terms.modify("POST", tmp_node).then(function (terms_group) {
				toastr.success("Category created!");
				console.log("debug id:");
				console.log(terms_group);
				vm.treeData.push({
					id: terms_group.short_id,
					parent: '#',
					text: terms_group.title[_lang] || "",
					icon: "glyphicon glyphicon-menu-hamburger"
				});
			}, function (err) {
				toastr.error("Couldn't create category!", err);
			});
		}

		function edit_sub(node_id, node, action_id, action_el) {
			var res = node.id.split("_");
			document.location.href = "/admin/term/" + res[0] + "/" + res[1] + "/";
		}

		function rename(node_id, node, action_id, action_el) {
			if (node.parent != '#') {
				return vm.edit_sub(node_id, node, action_id, action_el);
			}

			$terms.get({
				short_id: node.id
			}).then(function (_term) {
				if (_term.length !== 1) {
					toastr.error("Unexpected category details!");
					return;
				} else {
					_term = _term.pop();
				}

				var modalInstance = $uibModal.open({
					templateUrl: 'template/modal/terms/edit.html',
					controller: editCtrl,
					controllerAs: 'editCtrl',
					resolve: {
						data: function () {
							return {
								langs: _langs,
								term: _term
							};
						}
					}
				});

				modalInstance.result.then(function (data) {
					$terms.modify("POST", data.category).then(function () {
						toastr.success("term modified!");

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
				toastr.error("Couldn't get terms details!", err);
			});
		}

		function move(e, node) {
			node.node.original.parent = node.node.parent;
			var tmp_node = $category_util.nodeToCategory(node.node.original, vm);
			$terms.modify("post", tmp_node).then(function (category) {
				load_terms();
				toastr.success("Category moved!");
			}, function (err) {
				toastr.error("Couldn't move category!", err);
			});
		}

		function remove(node_id, node, action_id, action_el) {
			var category = vm.treeInstance.jstree(true).get_node(node.id);
			category = $category_util.nodeToCategory(category.original, vm);

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

			modalInstance.result.then(function () {
				$terms.modify("delete", category).then(function (category) {
					load_terms();
					toastr.success("Category deleted!");
				}, function (err) {
					toastr.error("Couldn't remove category!", err);
				});

			}, function () {
				//Cancel
			});

			// }, function(err) {
			// 	toastr.error("Couldn't check dependencies!", err);
			// });
		}

	}

	function create_newCtrl($uibModalInstance, data) {
		var vm = this;
		vm.data = data;
		vm.langs = {};
		vm.slug = "";
		vm.sizecharts = false;
		vm.prints = false;

		vm.ok = ok;
		vm.cancel = cancel;

		function ok() {
			$uibModalInstance.close({
				langs: vm.langs,
				slug: vm.slug,
				sizecharts: vm.sizecharts,
				prints: vm.prints
			});
		};

		function cancel() {
			$uibModalInstance.dismiss();
		}
	}

	function editCtrl($uibModalInstance, data) {
		var vm = this;
		vm.data = data;
		vm.ok = ok;
		vm.cancel = cancel;

		function ok() {
			$uibModalInstance.close({
				category: vm.data.category
			});
		};

		function cancel() {
			$uibModalInstance.dismiss();
		}

	}

	angular
		.module('todevise', ['ngAnimate', 'ui.bootstrap', 'ngJsTree', 'global-admin', 'global-desktop', 'api', 'util'])
		.controller('termsCtrl', controller)
		.controller('create_newCtrl', create_newCtrl)
		.controller('editCtrl', editCtrl);

}());