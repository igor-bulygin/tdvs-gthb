(function () {
	"use strict";

	function controller($scope, $timeout, $tag, $tag_util, $category_util, toastr, $uibModal, $cacheFactory) {
		var vm = this;
		vm.lang = _lang;
		vm.tag = _tag;
		vm.mus = _mus;
		vm.selected_mu = "";

		vm.c_mus = $cacheFactory("mus");
		angular.forEach(_mus, function (mus) {
			vm.c_mus.put(mus.value, mus);
		});

		vm.pending_dialog_type = false;
		vm.type_watch_paused = false;

		//Sort by path length
		vm.categories = $category_util.create_tree(_categories);

		vm.get_mu_type = get_mu_type;
		vm.get_input_type = get_input_type;
		vm.edit_dropdown_option = edit_dropdown_option;
		vm.create_freetext_option = create_freetext_option;
		vm.delete_option = delete_option;
		vm.cancel = cancel;
		vm.save = save;

		$scope.$watch("tagCtrl.tag.stock_and_price", function (_new, _old) {
			if (_new === true) {
				vm.tag.required = true;
			}
		});

		$scope.$watch("tagCtrl.tag.required", function (_new, _old) {
			if (_new === false && vm.tag.stock_and_price === true) {
				vm.tag.required = true;
			}
		});

		$scope.$watch("tagCtrl.tag.type", function (_new, _old) {
			if (_new !== _old && vm.type_watch_paused === false) {

				vm.pending_dialog_type = true;

				var modalInstance = $uibModal.open({
					templateUrl: 'template/modal/confirm.html',
					controller: 'confirmCtrl',
					resolve: {
						data: function () {
							return {
								title: "Are you sure?",
								text: "All current options (if any) will be removed"
							};
						}
					}
				});

				modalInstance.result.then(function () {
					vm.tag.options = [];
				}, function () {
					vm.type_watch_paused = true;
					vm.tag.type = _old;
					$timeout(function () {
						vm.type_watch_paused = false;
					}, 0);
				}).finally(function () {
					vm.pending_dialog_type = false;
				});
			}
		})

		function get_mu_type(v) {
			var _type = vm.c_mus.get(v);
			if (_type !== undefined)
				return _type.text;
		}

		function get_input_type(v) {
			return _tagoption_txt[v];
		}

		function edit_dropdown_option(index) {
			var _mod_option_index;
			if (index === -1) {
				vm.tag.options.push($tag_util.newDropdownOption());
				_mod_option_index = vm.tag.options.length - 1;
			} else {
				_mod_option_index = index;
			}

			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/tag/create_new.html',
				controller: create_newCtrl,
				controllerAs: 'create_newCtrl',
				resolve: {
					data: function () {
						return {
							langs: _langs,
							index: _mod_option_index,
							options: vm.tag.options
						};
					}
				}
			});

			modalInstance.result.then(function (data) {
				vm.tag.options = data.options;
			}, function () {
				// Remove the new, empty option we created (don't do anything if we were editing an option)
				if (index === -1) {
					vm.tag.options.pop();
				}
			});
		}

		function create_freetext_option() {
			vm.new_option["metric_type"] = vm.selected_mu;
			vm.new_option.type = parseInt(vm.freetext_type, 10);

			vm.tag.options.push(vm.new_option);

			vm.new_option = {};
		}

		function delete_option(index) {
			vm.tag.options.splice(index, 1);
		}

		function cancel() {
			vm.type_watch_paused = true;
			vm.tag = angular.copy(vm._shadow);

			$timeout(function () {
				vm.type_watch_paused = false;
			}, 0);
		}

		function save() {
			$tag.modify("POST", vm.tag).then(function () {
				toastr.success("Tag saved successfully!");
			}, function (err) {
				toastr.error("Failed saving tag!", err);
			});
		};

		vm._shadow = angular.copy(vm.tag);
	}

	function create_newCtrl($uibModalInstance, data) {
		var vm = this;

		vm.data = data;
		vm.colors = _colors;
		vm.colors_lookup = {};
		vm.get_color_from_value = get_color_from_value;
		vm.is_duplicated = is_duplicated;
		vm.ok = ok;
		vm.cancel = cancel;

		angular.forEach(_colors, function (color) {
			vm.colors_lookup[color.value] = color;
		});

		function get_color_from_value(value) {
			if (vm.colors_lookup.hasOwnProperty(value)) {
				return vm.colors_lookup[value];
			} else {
				return {};
			}
		};

		function is_duplicated(value) {
			var i = 0;
			angular.forEach(data.options, function (option) {
				if (option.value === value) i++;
			});
			return i === 0 || i === 1;
		}

		function ok() {
			$uibModalInstance.close({
				options: vm.data.options
			});
		}

		function cancel() {
			$uibModalInstance.dismiss();
		}
	}

	angular
		.module('todevise', ['ngAnimate', 'ui.bootstrap', 'angular-multi-select', 'global-admin', 'global-desktop', 'api', 'angular-sortable-view', 'ui.validate'])
		.controller('tagCtrl', controller)
		.controller('create_newCtrl', create_newCtrl);

}());