var todevise = angular.module('todevise', ['ui.bootstrap', 'angular-multi-select', 'global-admin', 'global-desktop', 'api']);

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

todevise.controller('tagsCtrl', ["$scope", "$timeout", "$tag", "$tag_util", "toastr", "$modal", "$compile", "$http", function($scope, $timeout, $tag, $tag_util, toastr, $modal, $compile, $http) {

	$scope.current_lang = _lang;

	$scope.categories = [];
	$scope.selectedCategories = [];

	$scope.renderPartial = function() {
		$scope.stop_watch_category_filter();

		$http.get(aus.syncToURL()).success(function(data, status) {
			angular.element('.body-content').html( $compile(data)($scope) );
			$scope.watch_category_filter();
		}).error(function(data, status) {
			toastr.error("Failed to refresh content!");
		});
	};

	$scope.watch_category_filter = function() {
		$scope.stop_watch_category_filter = $scope.$watch('selectedCategories', function(_new, _old) {
			if(_new && !angular.equals(_new, _old)) {
				$scope.filter_change(_new);
			}
		});
	};

	$scope.filter_change = function(_filters) {
		if(_filters.length === 0) {
			aus.remove("filters");
		} else {
			var _node = jsonpath.query(_filters, "$..[?(!@.sub && @.short_id)]");
			_node = _node.pop();

			aus.set("filters", {
				"categories": {"$in": [_node.short_id]}
			}, true);
		}

		$scope.renderPartial();
	};

	$scope.toggle_prop = function($event, tag_id, prop) {
		var _checkbox = $event.target;
		$tag.get({ short_id: tag_id }).then(function(tag) {
			if(tag.length !== 1) return;
			tag = tag.shift();

			tag[prop] = _checkbox.checked;
			$tag.modify("POST", tag).then(function(tag) {
				toastr.success("Tag modified!");
			}, function(err) {
				_checkbox.checked = !_checkbox.checked;
				toastr.error(err);
			});
		}, function(err) {
			toastr.error(err);
		});
	};

	$scope.delete = function(tag_id) {
		$tag.get({ short_id: tag_id }).then(function(tag) {
			if (tag.length !== 1) return;
			tag = tag.shift();

			$tag.delete(tag).then(function(data) {
				toastr.success("Tag deleted!");
				$scope.renderPartial();
			}, function(err) {
				toastr.error(err);
			});
		}, function(err) {
			toastr.error(err);
		});
	};

	$scope.create_new = function() {
		var modalInstance = $modal.open({
			templateUrl: 'template/modal/tag/create_new.html',
			controller: 'create_newCtrl',
			resolve: {
				data: function () {
					return {
						langs: _langs
					};
				}
			}
		});

		modalInstance.result.then(function(data) {
			var tag = $tag_util.newTag(data.description, data.langs);
			$tag.modify("POST", tag).then(function(tag) {
				toastr.success("Tag created!");
				$scope.renderPartial();
			}, function(err) {
				toastr.error("Couldn't create tag!", err);
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
		_categories.sort(function(a, b) {
			return a.path.length > b.path.length;
		});

		angular.forEach(angular.copy(_categories), function(_category) {
			if (_category.path === "/") {
				$scope.categories.push(_category);
			} else {
				var _path = _category.path.split("/");
				_path.pop();
				var _parent = _path.pop();

				jsonpath.apply($scope.categories, "$..[?(@.short_id=='" + _parent + "')]", function(obj) {
					if(!obj.hasOwnProperty("sub")) {
						obj.sub = [];
					}

					if(_category.short_id == _category_id) {
						_category.check = true;
					}
					obj.sub.push(_category);
					return obj;
				});

			}
		});

		$timeout(function() {
			$scope.watch_category_filter();
		}, 0);
	};

}]);

todevise.controller("create_newCtrl", function($scope, $modalInstance, data) {
	$scope.data = data;
	$scope.langs = {};
	$scope.description = "";

	$scope.ok = function() {
		$modalInstance.close({
			"langs": $scope.langs,
			"description": $scope.description
		});
	};

	$scope.cancel =  function() {
		$modalInstance.dismiss();
	};
});
