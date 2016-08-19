(function () {
	"use strict";

	function controller($rootScope, $scope, $timeout, $sizechart, $sizechart_util, $category_util, toastr, $uibModal, $compile, $http) {
		var vm = this;
		vm.lang = _lang;
		vm.listener;
		vm.categories = [];
		vm.selectedCategories = [];

		vm.renderPartial = renderPartial;
		vm.delete = delete_size_chart;
		vm.create_new = create_new;

		function renderPartial() {
			vm.listener();

			$http.get(aus.syncToUrl()).success(function (data, status) {
				angular.element('.body-content').html($compile(data)($scope));
			}).error(function (data, status) {
				toastr.error("Failed to refresh content!");
			});
		};

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

		function delete_size_chart(size_chart_id) {
			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/confirm.html',
				controller: 'confirmCtrl',
				resolve: {
					data: function () {
						return {
							title: "Are you sure?",
							text: "You are about to delete a size chart!"
						};
					}
				}
			});

			modalInstance.result.then(function () {
				$sizechart.get({
					short_id: size_chart_id
				}).then(function (sizechart) {
					if (sizechart.length !== 1) return;
					sizechart = sizechart.shift();

					$sizechart.delete(sizechart).then(function (data) {
						toastr.success("Size chart deleted!");
						vm.renderPartial();
					}, function (err) {
						toastr.error("Couldn't remove size chart!", err);
					});
				}, function (err) {
					toastr.error("Couldn't find size chart!", err);
				});
			}, function () {
				//Cancel
			});
		}

		function create_new() {
			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/sizechart/create_new.html',
				controller: create_newCtrl,
				controllerAs: 'create_newCtrl',
				resolve: {
					data: function () {
						return {
							lang: _lang,
							langs: _langs,
							sizecharts: _sizecharts_template
						};
					}
				}
			});

			modalInstance.result.then(function (data) {
				vm._tmp_sizechart = {};
				var _d = data.selectedSizeChartTemplate;
				var _new = $sizechart_util.newSizeChart(data.langs);

				if (_d.length === 1) {
					delete _new["short_id"];
					vm._tmp_sizechart = angular.merge(_d[0], _new);
				} else {
					vm._tmp_sizechart = _new;
				}

				$sizechart.get({
					short_id: vm._tmp_sizechart.short_id
				}).then(function (sizechart) {
					if (sizechart.length === 0) return;

					vm._tmp_sizechart = angular.merge({}, sizechart.pop(), vm._tmp_sizechart);
					vm._tmp_sizechart["short_id"] = "new";
				}, function (err) {
					toastr.error("Couldn't get size chart!", err);
				}).finally(function () {
					$sizechart.modify("POST", vm._tmp_sizechart).then(function (sizechart) {
						toastr.success("Size chart created!");
						vm.renderPartial();
					}, function (err) {
						toastr.error("Couldn't create size chart!", err);
					});
				});

			}, function () {
				//Cancel
			});
		}

		function init() {
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
		vm.sizechart = null;

		vm.ok = ok;
		vm.cancel = cancel;

		function ok() {
			$uibModalInstance.close({
				langs: vm.langs,
				selectedSizeChartTemplate: vm.selectedSizeChartTemplate
			});
		};

		function cancel() {
			$uibModalInstance.dismiss();
		}
	}

	angular
		.module('todevise', ['ngAnimate', 'ui.bootstrap', 'angular-multi-select', 'global-admin', 'global-desktop', 'api'])
		.controller('sizeChartsCtrl', controller)
		.controller('create_newCtrl', create_newCtrl)

}());