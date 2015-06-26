var todevise = angular.module('todevise', ['ui.bootstrap', 'angular-multi-select', 'global-admin', 'global-desktop', 'api', 'angular-sortable-view', 'ui.validate']);

todevise.controller('sizeChartCtrl', ["$scope", "$timeout", "$sizechart", "$sizechart_util", "$category_util", "toastr", "$modal", function($scope, $timeout, $sizechart, $sizechart_util, $category_util, toastr, $modal) {
	$scope.lang = _lang;
	$scope.sizechart = _sizechart;
	$scope.mus = _mus;
	$scope.api = {};
	$scope.api_mus = {};

	$scope.countries_lookup = [];
	angular.forEach(_countries, function(country) {
		$scope.countries_lookup[country.country_code] = country.country_name[_lang];
	});

	//Sort by path length
	$category_util.sort(_categories);
	$scope.categories = $category_util.create_tree(_categories);
	//Select pre-selected categories and metric unit
	$timeout(function() {
		angular.forEach($scope.sizechart.categories, function(id) {
			$scope.api.select(id);
		});
		$scope.api_mus.select($scope.sizechart.metric_units);

		//After the directive is done rendering itself start monitoring the selected categories
		$timeout(function() {
			$scope.$watch("selectedCategories", function(_new, _old) {
				if(_new !== _old) {
					$scope.sizechart.categories = [];
					var _selected_categories = jsonpath.query(_new, "$..[?(!@.sub && @.short_id)]");
					angular.forEach(_selected_categories, function(_cat) {
						$scope.sizechart.categories.push(_cat.short_id);
					});
				}
			});
		}, 0);

	}, 0);

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
		var modalInstance = $modal.open({
			templateUrl: 'template/modal/sizechart/create_new_country.html',
			controller: 'create_new_countryCtrl',
			resolve: {
				data: function () {
					return {
						countries_lookup: $scope.countries_lookup
					};
				}
			}
		});

		modalInstance.result.then(function(data) {
			var _country = data.country_code;
			if(_country.length !== 1) return;

			_country = _country.pop();

			$scope.sizechart.countries.push(_country.country_code);

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
		var modalInstance = $modal.open({
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

	$scope._shadow = angular.copy($scope.sizechart);
}]);

todevise.controller("create_new_countryCtrl", function($scope, $modalInstance, data) {
	$scope.lang = _lang;
	$scope.countries = _countries;
	$scope.countries_lookup = data.countries_lookup;

	$scope.ok = function() {
		$modalInstance.close({
			"country_code": $scope.selected_country
		});
	};

	$scope.cancel =  function() {
		$modalInstance.dismiss();
	};
});

todevise.controller("create_new_columnCtrl", function($scope, $modalInstance, data) {
	$scope.data = data;

	$scope.ok = function() {
		$modalInstance.close({
			"column": $scope.data.column
		});
	};

	$scope.cancel =  function() {
		$modalInstance.dismiss();
	};
});
