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

todevise.controller('tagsCtrl', ["$rootScope", "$scope", "$timeout", "$tag", "$tag_util", "$category_util", "toastr", "$uibModal", "$compile", "$http", function($rootScope, $scope, $timeout, $tag, $tag_util, $category_util, toastr, $uibModal, $compile, $http) {

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
				toastr.error("Couldn't delete tag!", err);
			});
		}, function(err) {
			toastr.error("Couldn't find tag!", err);
		});
	};

	$scope.create_new = function() {
		var modalInstance = $uibModal.open({
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
	$scope.description = "";

	$scope.ok = function() {
		$uibModalInstance.close({
			"langs": $scope.langs,
			"description": $scope.description
		});
	};

	$scope.cancel =  function() {
		$uibModalInstance.dismiss();
	};
});
