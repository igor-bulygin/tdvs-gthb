(function () {
	"use strict";

	function controller($rootScope, $scope, $timeout, $tag, $tag_util, $category_util, toastr, $uibModal, $compile, $http) {
		var vm = this;

		vm.lang = _lang;
		vm.listener;
		vm.categories = [];
		vm.selectedCategories = [];

		vm.renderPartial = renderPartial;
		vm.toggle_prop = toggle_prop;
		vm.delete = delete_tag;
		vm.create_new = create_new;

		function renderPartial() {
			vm.listener();

			$http.get(aus.syncToURL()).success(function (data, status) {
				angular.element('.body-content').html($compile(data)($scope));
			}).error(function (data, status) {
				toastr.error("Failed to refresh content!");
			});
		}

		vm.listener = $scope.$on('ams_toggle_check_node', function (event, args) {
			if (args.name !== "categories") return;

			$timeout(function () {
				if (vm.selectedCategories.length === 0) {
					aus.remove("filters");
				} else {
					aus.set("filters", {
						categories: {
							"$in": [vm.selectedCategories[0].short_id]
						}
					}, true);
				}

				vm.renderPartial();
			}, 0);
		});

		function toggle_prop($event, tag_id, prop) {
			var _checkbox = $event.target;
			$tag.get({
				short_id: tag_id
			}).then(function (tag) {
				if (tag.length !== 1) return;
				tag = tag.shift();

				tag[prop] = _checkbox.checked;
				$tag.modify("POST", tag).then(function (tag) {
					toastr.success("Tag modified!");
				}, function (err) {
					_checkbox.checked = !_checkbox.checked;
					toastr.error(err);
				});
			}, function (err) {
				toastr.error(err);
			});
		}

		function delete_tag(tag_id) {
			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/confirm.html',
				controller: 'confirmCtrl',
				resolve: {
					data: function () {
						return {
							title: "Are you sure?",
							text: "You are about to delete a tag!"
						};
					}
				}
			});

			modalInstance.result.then(function () {
				$tag.get({
					short_id: tag_id
				}).then(function (tag) {
					if (tag.length !== 1) return;
					tag = tag.shift();

					$tag.delete(tag).then(function (data) {
						toastr.success("Tag deleted!");
						vm.renderPartial();
					}, function (err) {
						toastr.error("Couldn't delete tag!", err);
					});
				}, function (err) {
					toastr.error("Couldn't find tag!", err);
				});
			}, function () {
				//Cancel
			});
		}

		function create_new() {
			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/tag/create_new.html',
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
				var tag = $tag_util.newTag(data.description, data.langs);
				$tag.modify("POST", tag).then(function (tag) {
					toastr.success("Tag created!");
					vm.renderPartial();
				}, function (err) {
					toastr.error("Couldn't create tag!", err);
				});
			}, function () {
				//Cancel
			});
		}

		function init() {
			//Get filters, if any
			var _filters = aus.get("filters", true);
			var _category_id = null;
			if (_filters !== null) {
				_category_id = _filters.categories["$in"].pop();
			}

			//Sort by path length
			_categories = $category_util.sort(_categories);

			vm.categories = $category_util.create_tree(_categories);

			if (_category_id !== null) {
				$timeout(function () {
					$rootScope.$broadcast('ams_do_toggle_check_node', {
						name: 'categories',
						item: {
							short_id: _category_id
						}
					});
				}, 0);
			}
		}

		init();

	}

	function create_newCtrl($uibModalInstance, data) {
		var vm = this;
		
		vm.data = data;
		vm.langs = {};
		vm.description = "";

		vm.ok = ok;
		vm.cancel = cancel;

		function ok() {
			$uibModalInstance.close({
				langs: vm.langs,
				description: vm.description
			});
		};

		function cancel() {
			$uibModalInstance.dismiss();
		};
	}

	angular
		.module('todevise', ['ngAnimate', 'ui.bootstrap', 'angular-multi-select', 'global-admin', 'global-desktop', 'api'])
		.controller('tagsCtrl', controller)
		.controller('create_newCtrl', create_newCtrl);

}());