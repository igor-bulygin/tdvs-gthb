(function () {

	function controller($scope, $timeout, $sizechart, $sizechart_util, $category_util, toastr, $uibModal) {
		var vm = this;

		vm.lang = _lang;
		vm.sizechart = _sizechart;

		vm.countries_lookup = _countries_lookup;
		vm.new_country = new_country;
		vm.move_country = move_country;
		vm.delete_country = delete_country;
		vm.new_column = new_column;
		vm.delete_column = delete_column;
		vm.move_column = move_column;
		vm.new_row = new_row;
		vm.removeRow = removeRow;
		vm.cancel = cancel;
		vm.save = save;

		//Sort by path length
		_categories = $category_util.sort(_categories);
		angular.forEach(_categories, function (category) {
				if (vm.sizechart.categories.includes(category.short_id)) {
					category.check=true;
				}
		});
		vm.categories = $category_util.create_tree(_categories);
		vm._shadow = JSON.parse(JSON.stringify(vm.sizechart));

		$scope.$on('ams_toggle_ckeck_node', function (event, args) {
			if (args.name !== "metric_unit") return;

			vm.convertFrom = args.item.smallest;
			vm.convertTo = args.item.value;
		});

		$scope.$watch("[sizeChartCtrl.sizechart.countries, sizeChartCtrl.sizechart.columns]", function () {
			vm.table_header = [];
			angular.forEach(vm.sizechart.countries, function (country) {
				vm.table_header.push(vm.countries_lookup[country]);
			});

			angular.forEach(vm.sizechart.columns, function (column) {
				vm.table_header.push(column[vm.lang]);
			});
		}, true);

		function new_country() {
			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/sizechart/create_new_country.html',
				controller: create_new_countryCtrl,
				controllerAs: 'create_new_countryCtrl',
				resolve: {
					data: function () {
						return {};
					}
				}
			});

			modalInstance.result.then(function (data) {
				vm.sizechart.countries.push(data.country_code);
				angular.forEach(vm.sizechart.values, function (row) {
					row.splice(vm.sizechart.countries.length - 1, 0, 0);
				});
			}, function () {
				//Cancel
			});
		}

		function move_country(from, to) {
			angular.forEach(vm.sizechart.values, function (row) {
				row.splice(to, 0, row.splice(from, 1)[0]);
			});
		}

		function delete_country(index) {
			vm.sizechart.countries.splice(index, 1);
			angular.forEach(vm.sizechart.values, function (row) {
				row.splice(index, 1);
			});
		}

		function new_column() {
			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/sizechart/create_new_column.html',
				controller: create_new_columnCtrl,
				controllerAs: 'create_new_columnCtrl',
				resolve: {
					data: function () {
						return {
							langs: _langs
						};
					}
				}
			});

			modalInstance.result.then(function (data) {
				vm.sizechart.columns.push(data.column);

				angular.forEach(vm.sizechart.values, function (row) {
					row.push(0);
				});
			}, function () {
				//Cancel
			});
		}

		function delete_column(index) {
			vm.sizechart.columns.splice(index, 1);
			angular.forEach(vm.sizechart.values, function (row) {
				row.splice(index, 1);
			});
		}

		function move_column(from, to) {
			vm.sizechart.columns.splice(index, 1);
			angular.forEach(vm.sizechart.values, function (row) {
				row.splice(index, 1);
			});
		}

		function new_row() {
			var _len = vm.table_header.length;
			var _data = Array.apply(null, Array(_len)).map(Number.prototype.valueOf, 0);
			vm.sizechart.values.push(_data);
		}

		function removeRow(index) {
			vm.sizechart.values.splice(index, 1);
		}

		function cancel() {
			vm.sizechart = angular.copy(vm._shadow);

			$timeout(function () {
				vm.type_watch_paused = false;
			}, 0);
		}

		function save() {
			if (!angular.isUndefined(vm.sizechart.metric_unit) && vm.sizechart.metric_unit.length>0) {
				vm.sizechart.metric_unit=vm.sizechart.metric_unit[0].value;
			}
			$sizechart.modify("POST", vm.sizechart).then(function () {
				toastr.success("Size chart saved successfully!");
			}, function (err) {
				toastr.error("Failed saving size chart!", err);
			});
		}

	}

	function create_new_countryCtrl($scope, $uibModalInstance, data) {
		var vm = this;
		vm.lang = _lang;
		vm.ok = ok;
		vm.cancel = cancel;
		console.log(data);

		$scope.$on('ams_toggle_check_node', function (event, args) {
			if (args.name !== "country") return;
			vm.selected_country = args.item.country_code;
		});

		function ok() {
			$uibModalInstance.close({
				country_code: vm.selected_country
			});
		};

		function cancel() {
			$uibModalInstance.dismiss();
		};

	}

	function create_new_columnCtrl($uibModalInstance, data) {
		var vm = this;
		vm.data = data;
		vm.ok = ok;
		vm.cancel = cancel;

		function ok() {
			$uibModalInstance.close({
				column: vm.data.column
			});
		};

		function cancel() {
			$uibModalInstance.dismiss();
		};

	}

	angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'angular-multi-select', 'angular-unit-converter', 'global-admin', 'global-desktop', 'api', 'angular-sortable-view', 'ui.validate'])
		.controller('sizeChartCtrl', controller)
		.controller('create_new_countryCtrl', create_new_countryCtrl)
		.controller('create_new_columnCtrl', create_new_columnCtrl);

}());