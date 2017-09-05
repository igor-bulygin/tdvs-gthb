(function () {
	"use strict";

	function controller($faqs, $category_util, $location, toastr, $uibModal) {
		var vm = this;

		vm.treeData = [];
		vm.langs = _langs;
		vm.oneAtATime = false;
		vm.faq_id = _faq_id;
		vm.faq_subid = _faq_subid;
		vm.faq = {};

		console.log(_faq_id);
		console.log(_faq_subid);

		vm.load = load;
		vm.save = save;
		vm.create_sub = create_sub;
		vm.close = close;

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

		vm.load();

		function load() {
			$faqs.get({
				"short_id": vm.faq_id
			}).then(function (_faq) {
				if (_faq.length !== 1) {
					toastr.error("Unexpected category details!");
					return;
				} else {
					_faq = _faq.pop();
					console.log(_faq);
					vm.faq = _faq;
					if (vm.faq_subid.length <= 0) {
						vm.faq_subid = vm.faq.faqs.push({
							answer: {},
							question: {}
						});
						vm.faq_subid = vm.faq_subid - 1;
					}
					vm.subfaq = vm.faq.faqs[vm.faq_subid];
					console.log(vm.subfaq);
				}
			}, function (err) {
				toastr.error("Couldn't get faqs details!", err);
			});
		}

		function save() {
			vm.faq.faqs[vm.faq_subid] = vm.subfaq;

			$faqs.modify("POST", vm.faq).then(function () {
				toastr.success("Faq modified!");

				close();

				//var _node = $category_util.categoryToNode(data.category);
				//var _current = _.findWhere(vm.treeData, {id: data.category.short_id});
				//angular.merge(_current, _node);

			}, function (err) {
				toastr.error("Couldn't modify category!", err);
			});
		}

		function close() {
			document.location.href = currentHost() + "/admin/faqs";
		}

		function create_sub(node_id, node, action_id, action_el) {
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
					icon: "glyphicon glyphicon-menu-hamburger"
				});
			}, function (err) {
				toastr.error("Couldn't create category!", err);
			});
		}
	}

	angular
		.module('todevise', ['ngAnimate', 'ui.bootstrap', 'ngJsTree', 'global-admin', 'global-desktop', 'api'])
		.controller('faqCtrl', controller);

}());