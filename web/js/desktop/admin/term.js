(function () {
	"use strict";

	function controller($term, $category_util, $location, toastr, $uibModal) {
		var vm = this;
		console.log("HEY!")
		vm.treeData = [];
		vm.langs = _langs;
		vm.oneAtATime = false;
		vm.term_id = _term_id;
		vm.term_subid = _term_subid;
		vm.term = {};

		vm.groups = [
			{
				title: 'Dynamic Group Header - 1',
				content: 'Dynamic Group Body - 1'
			},
			{
				title: 'Dynamic Group Header - 2',
				content: 'Dynamic Group Body - 2'
			}
		];

		vm.status = {
			isCustomHeaderOpen: false,
			isFirstOpen: true,
			isFirstDisabled: false
		};

		vm.load = load;
		vm.save = save;
		vm.create_sub = create_sub;
		vm.load();

		function load() {
			$term.get({
				short_id: vm.term_id
			}).then(function (_term) {
				if (_term.length !== 1) {
					console.log(_term);
					toastr.error("Unexpected category details!");
					return;
				} else {
					_term = _term.pop();
					console.log(_term);

					vm.term = _term;
					if (vm.term_subid.length <= 0) {
						vm.term_subid = vm.term.terms.push({
							answer: {},
							question: {}
						});
						vm.term_subid = vm.term_subid - 1;
					}
					vm.subterm = vm.term.terms[vm.term_subid];

					console.log(vm.subterm);
				}

			}, function (err) {
				toastr.error("Couldn't get terms details!", err);
			});
		}

		function save() {
			vm.term.terms[vm.term_subid] = vm.subterm;

			$term.modify("POST", vm.term).then(function () {
				toastr.success("term modified!");

				//var _node = $category_util.categoryToNode(data.category);
				//var _current = _.findWhere(vm.treeData, {id: data.category.short_id});
				//angular.merge(_current, _node);

			}, function (err) {
				toastr.error("Couldn't modify category!", err);
			});
		}

		function create_sub(node_id, node, action_id, action_el) {
			var path = "/";
			if (node !== undefined) {
				path += vm.treeInstance.jstree(true).get_path(node, "/", true) + "/";
			}

			$term.modify("POST", tmp_node).then(function (terms_group) {
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
	}

	angular
		.module('todevise', ['ngAnimate', 'ui.bootstrap', 'ngJsTree', 'global-admin', 'global-desktop', 'api'])
		.controller('termCtrl', controller);

}());