var todevise = angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'angular-multi-select', 'global-admin', 'global-desktop', 'api']);

todevise.run(["$http", function($http) {
	$http.defaults.headers.get = {
		"X-Requested-With":  "XMLHttpRequest"
	};
}]);

todevise.config(['$provide', function($provide) {
	$provide.decorator('$browser', ['$delegate', function ($delegate) {
		$delegate.onUrlChange = function() {};
		$delegate.url = function() { return ""; };
		return $delegate;
	}]);
}]);

todevise.controller('sizeChartsCtrl', ["$rootScope", "$scope", "$timeout", "$sizechart", "$sizechart_util", "$category_util", "toastr", "$uibModal", "$compile", "$http", function($rootScope, $scope, $timeout, $sizechart, $sizechart_util, $category_util, toastr, $uibModal, $compile, $http) {

	$scope.lang = _lang;
	$scope.listener;
	$scope.categories = [];
	$scope.selectedCategories = [];

	$scope.renderPartial = function() {
		$scope.listener();

		$http.get(aus.syncToURL()).success(function(data, status) {
			angular.element('.body-content').html( $compile(data)($scope) );
		}).error(function(data, status) {
			toastr.error("Failed to refresh content!");
		});
	};

	$scope.listener = $scope.$on('ams_toggle_check_node', function (event, args) {
		if (args.name !== "categories") return;

		$timeout(function () {
			if($scope.selectedCategories.length === 0) {
				aus.remove("filters");
			} else {
				aus.set("filters", {
					"categories": {"$in": [$scope.selectedCategories[0].short_id]}
				}, true);
			}

			$scope.renderPartial();
		}, 0);
	});

	$scope.delete = function(size_chart_id) {
		$sizechart.get({ short_id: size_chart_id }).then(function(sizechart) {
			if (sizechart.length !== 1) return;
			sizechart = sizechart.shift();

			$sizechart.delete(sizechart).then(function(data) {
				toastr.success("Size chart deleted!");
				$scope.renderPartial();
			}, function(err) {
				toastr.error("Couldn't remove size chart!", err);
			});
		}, function(err) {
			toastr.error("Couldn't find size chart!", err);
		});
	};

	$scope.create_new = function() {
		var modalInstance = $uibModal.open({
			templateUrl: 'template/modal/sizechart/create_new.html',
			controller: 'create_newCtrl',
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

		modalInstance.result.then(function(data) {
			$scope._tmp_sizechart = {};
			var _d = data.selectedSizeChartTemplate;
			var _new = $sizechart_util.newSizeChart(data.langs);

			if(_d.length === 1) {
				delete _new["short_id"];
				$scope._tmp_sizechart = angular.merge(_d[0], _new);
			} else {
				$scope._tmp_sizechart = _new;
			}

			$sizechart.get({"short_id": $scope._tmp_sizechart.short_id}).then(function(sizechart) {
				if(sizechart.length === 0) return;

				$scope._tmp_sizechart = angular.merge({}, sizechart.pop(), $scope._tmp_sizechart);
				$scope._tmp_sizechart["short_id"] = "new";
			}, function(err) {
				toastr.error("Couldn't get size chart!", err);
			}).finally(function() {
				$sizechart.modify("POST", $scope._tmp_sizechart).then(function(sizechart) {
					toastr.success("Size chart created!");
					$scope.renderPartial();
				}, function(err) {
					toastr.error("Couldn't create size chart!", err);
				});
			});

		}, function () {
			//Cancel
		});
	};

	$scope.init = function() {
		//Get filters, if any
		var _filters = aus.get("filters", true);
		var _category_id = null;
		if(_filters !== null) {
			_category_id = _filters.categories["$in"].pop();
		}

		//Sort by path length
		_categories = $category_util.sort(_categories);

		$scope.categories = $category_util.create_tree(_categories);

		if (_category_id !== null) {
			$timeout(function () {
				$rootScope.$broadcast('ams_do_toggle_check_node', {
					name: 'categories',
					item: {short_id: _category_id}
				});
			}, 0);
		}
	};

}]);

todevise.controller("create_newCtrl", function($scope, $uibModalInstance, data) {
	$scope.data = data;

	$scope.langs = {};
	$scope.sizechart = null;

	$scope.ok = function() {
		$uibModalInstance.close({
			"langs": $scope.langs,
			"selectedSizeChartTemplate": $scope.selectedSizeChartTemplate
		});
	};

	$scope.cancel = function() {
		$uibModalInstance.dismiss();
	};
});
