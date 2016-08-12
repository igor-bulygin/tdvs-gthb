(function () {
	"use strict";

	function faqsCtrl($faqs, $category_util, $location, toastr, $uibModal, treeService) {
		var vm = this;

		vm.treeData = [];
		vm.langs = _langs;

		vm.treeConfig = treeService.treeDefaultConfig("todevise_faqs_jstree", vm);

		vm.load_faqs = function () {
			$faqs.get().then(function (_faqs) {
				//Empty the tada holder (if there is anything)
				vm.treeData.splice(0, vm.treeData.length);
				console.log(_faqs);

				//Fill the new values
				angular.forEach(_faqs, function (faqs_group, key) {
					//console.log(faqs_group.title[_lang]);
					var short_id = faqs_group.short_id;
					vm.treeInstance.jstree(true).add_action(short_id, {
						id: "action_add",
						class: "action_add pull-right",
						text: "",
						after: true,
						selector: "a",
						event: "click",
						callback: vm.edit_faq
					});

					//a group
					vm.treeData.push({
						id: short_id,
						parent: "#",
						text: faqs_group.title[_lang] || "",
						icon: "none"
					});

					if (faqs_group.faqs.length > 0) {
						//faqs inside group
						angular.forEach(faqs_group.faqs, function (faqs, key) {
							console.log(faqs.question[_lang]);
							vm.treeData.push({
								id: short_id + "_" + key,
								short_id: key,
								parent: short_id,
								text: faqs.question[_lang] || "",
								icon: "none"
							});
						});
					}
				});
				console.log(vm.treeData);
				toastr.success("Categories loaded!");
			}, function (err) {
				//callback error
				toastr.error("Couldn't load categories!", err);
			});
		};

		vm.readyCB = function () {
			vm.treeInstance.jstree(true).add_action('all', {
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

			vm.load_faqs();
		}

		vm.search = function () {
			vm.treeInstance.jstree(true).search(vm.searchModel);
		};

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
				templateUrl: 'template/modal/faqs/create_new.html',
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
				var tmp_node = {
					short_id: "new",
					title: data.langs
				};

				$faqs.modify("POST", tmp_node).then(function (faqs_group) {
					toastr.success("Category created!");
					console.log("debug id:");
					console.log(faqs_group);
					vm.treeData.push({
						id: faqs_group.short_id,
						parent: '#',
						text: faqs_group.title[_lang] || "",
						icon: "none"
					});
				}, function (err) {
					toastr.error("Couldn't create category!", err);
				});
			}, function () {
				//Cancel
			});
		};

		vm.edit_faq = function (node_id, node, action_id, action_el) {
			//vm.viewtoggle = !vm.viewtoggle;
			//TODO: Get this url from Yii url generator
			document.location.href = "/admin/faq" + node.id + "/";
		};

		vm.create_sub = function (node_id, node, action_id, action_el) {
			var path = "/";
			if (node !== undefined) {
				path += vm.treeInstance.jstree(true).get_path(node, "/", true) + "/";
			}

			$faqs.modify("POST", tmp_node).then(function (faqs_group) {
				toastr.success("Category created!");
				console.log("debug id:");
				console.log(faqs_group);
				vm.treeData.push({
					id: faqs_group.short_id,
					parent: '#',
					text: faqs_group.title[_lang] || "",
					icon: "glyphicon glyphicon-menu-hamburguer"
				});
			}, function (err) {
				toastr.error("Couldn't create category!", err);
			});
		};

		vm.edit_sub = function (node_id, node, action_id, action_el) {
			var res = node.id.split("_");
			document.location.href = "/admin/faq/" + res[0] + "/" + res[1] + "/";
		}

		vm.rename = function (node_id, node, action_id, action_el) {
			if (node.parent != '#') {
				return vm.edit_sub(node_id, node, action_id, action_el);
			}

			$faqs.get({
				short_id: node.id
			}).then(function (_faq) {
				if (_faq.length !== 1) {
					toastr.error("Unexpected category details!");
					return;
				} else {
					_faq = _faq.pop();
				}

				var modalInstance = $uibModal.open({
					templateUrl: 'template/modal/faqs/edit.html',
					controller: editCtrl,
					controllerAs: "editCtrl",
					resolve: {
						data: function () {
							return {
								langs: _langs,
								faq: _faq
							};
						}
					}
				});

				modalInstance.result.then(function (data) {
					$faqs.modify("POST", data.category).then(function () {
						toastr.success("Faq modified!");

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
				toastr.error("Couldn't get faqs details!", err);
			});
		}

		vm.move = function (e, node) {
			node.node.original.parent = node.node.parent;
			var tmp_node = $category_util.nodeToCategory(node.node.original, vm);
			$faqs.modify("POST", tmp_node).then(function (category) {
				vm.load_faqs();
				toastr.success("Category moved!");
			}, function (err) {
				toastr.error("Couldn't move category!", err);
			});
		};

		vm.remove = function (node_id, node, action_id, action_el) {
			var category = vm.treeInstance.jstree(true).get_node(node.id);
			category = $category_util.nodeToCategory(category.original, vm);

			//Check for sub-categories that depend on this one so we can remove those too
			//$category_util.subCategories(category).then(function(subcategories) {})
			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/confirm.html',
				controller: confirmCtrl,
				controllerAs: 'confirmCtrl',
				resolve: {
					data: function () {
						return {
							title: "Are you sure?",
							text: "Faqs group will be removed"
						};
					}
				}
			});

			modalInstance.result.then(function () {
				$faqs.modify("delete", category).then(function (category) {
					vm.load_faqs();
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
				prints: vm.prints
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
		};
	}


	angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'ngJsTree', 'global-admin', 'global-desktop', 'api', 'util'])
		.controller('faqsCtrl', faqsCtrl)
		.controller('create_newCtrl', create_newCtrl)
		.controller('editCtrl', editCtrl);

}());