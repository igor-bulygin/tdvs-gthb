var todevise = angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'angular-multi-select', 'angular-unit-converter', 'global-admin', 'global-desktop', 'api', 'angular-sortable-view', 'ui.validate']);

todevise.controller('sizeChartCtrl', ["$scope", "$timeout", "$sizechart", "$sizechart_util", "$category_util", "toastr", "$uibModal", function($scope, $timeout, $sizechart, $sizechart_util, $category_util, toastr, $uibModal) {
	$scope.lang = _lang;
	$scope.sizechart = _sizechart;

	$scope.countries_lookup = _countries_lookup;

	//Sort by path length
	_categories = $category_util.sort(_categories);
	$scope.categories = $category_util.create_tree(_categories);

	$scope.$on('ams_toggle_check_node', function (event, args) {
		if (args.name !== "metric_unit") return;

		$scope.convertFrom = args.item.smallest;
		$scope.convertTo = args.item.value;
	});

	$scope.$watch("[sizechart.countries, sizechart.columns]", function() {
		$scope.table_header = [];
		angular.forEach($scope.sizechart.countries, function(country) {
			$scope.table_header.push($scope.countries_lookup[country]);
		});

		angular.forEach($scope.sizechart.columns, function(column) {
			$scope.table_header.push(column[$scope.lang]);
		});
	}, true);

	$scope.new_country = function() {
		var modalInstance = $uibModal.open({
			templateUrl: 'template/modal/sizechart/create_new_country.html',
			controller: 'create_new_countryCtrl',
			resolve: {
				data: function () {
					return {};
				}
			}
		});

		modalInstance.result.then(function(data) {
			$scope.sizechart.countries.push(data.country_code);

			angular.forEach($scope.sizechart.values, function(row) {
				row.splice($scope.sizechart.countries.length - 1, 0, 0);
			});
		}, function () {
			//Cancel
		});
	};

	$scope.move_country = function(from, to) {
		angular.forEach($scope.sizechart.values, function(row) {
			row.splice(to, 0, row.splice(from, 1)[0]);
		});
	};

	$scope.delete_country = function(index) {
		$scope.sizechart.countries.splice(index, 1);
		angular.forEach($scope.sizechart.values, function(row) {
			row.splice(index, 1);
		});
	};

	$scope.new_column = function() {
		var modalInstance = $uibModal.open({
			templateUrl: 'template/modal/sizechart/create_new_column.html',
			controller: 'create_new_columnCtrl',
			resolve: {
				data: function () {
					return {
						langs: _langs
					};
				}
			}
		});

		modalInstance.result.then(function(data) {
			$scope.sizechart.columns.push(data.column);

			angular.forEach($scope.sizechart.values, function(row) {
				row.push(0);
			});
		}, function () {
			//Cancel
		});
	};

	$scope.delete_column = function(index) {
		$scope.sizechart.columns.splice(index, 1);
		angular.forEach($scope.sizechart.values, function(row) {
			row.splice(index, 1);
		});
	};

	$scope.move_column = function(from, to) {
		var _mod = $scope.sizechart.countries.length;
		from += _mod;
		to += _mod;
		angular.forEach($scope.sizechart.values, function(row) {
			row.splice(to, 0, row.splice(from, 1)[0]);
		});
	};

	$scope.new_row = function() {
		var _len = $scope.table_header.length;
		var _data = Array.apply(null, Array(_len)).map(Number.prototype.valueOf, 0);
		$scope.sizechart.values.push(_data);
	};

	$scope.cancel = function() {
		$scope.sizechart = angular.copy($scope._shadow);

		$timeout(function() {
			$scope.type_watch_paused = false;
		}, 0);
	};

	$scope.save = function() {
		$sizechart.modify("POST", $scope.sizechart).then(function() {
			toastr.success("Size chart saved successfully!");
		}, function(err) {
			toastr.error("Failed saving size chart!", err);
		});
	};

	$scope._shadow = JSON.parse(JSON.stringify($scope.sizechart));
}]);

todevise.controller("create_new_countryCtrl", function($scope, $uibModalInstance, data) {
	$scope.lang = _lang;

	$scope.$on('ams_toggle_check_node', function(event, args) {
		if (args.name !== "country") return;
		$scope.selected_country = args.item.country_code;
	});

	$scope.ok = function() {
		$uibModalInstance.close({
			"country_code": $scope.selected_country
		});
	};

	$scope.cancel =  function() {
		$uibModalInstance.dismiss();
	};
});

todevise.controller("create_new_columnCtrl", function($scope, $uibModalInstance, data) {
	$scope.data = data;

	$scope.ok = function() {
		$uibModalInstance.close({
			"column": $scope.data.column
		});
	};

	$scope.cancel = function() {
		$uibModalInstance.dismiss();
	};
});
