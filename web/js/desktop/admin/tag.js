var todevise = angular.module('todevise', ['ui.bootstrap', 'angular-multi-select', 'global-admin', 'global-desktop', 'api', 'angular-sortable-view', 'ui.validate']);

todevise.controller('tagCtrl', ["$scope", "$timeout", "$tag", "$tag_util", "$category_util", "toastr", "$modal", function($scope, $timeout, $tag, $tag_util, $category_util, toastr, $modal) {
	$scope.lang = _lang;
	$scope.tag = _tag;
	$scope.mus = _mus;
	$scope.api = {};

	$scope.pending_dialog_type = false;
	$scope.type_watch_paused = false;

	//Sort by path length
	$category_util.sort(_categories);
	$scope.categories = $category_util.create_tree(_categories);
	//Select pre-selected categories
	$timeout(function() {
		angular.forEach($scope.tag.categories, function(id) {
			$scope.api.select(id);
		});

		//After the directive is done rendering itself start monitoring the selected categories
		$timeout(function() {
			$scope.$watch("selectedCategories", function(_new, _old) {
				if(_new !== _old) {
					$scope.tag.categories = [];
					var _selected_categories = jsonpath.query(_new, "$..[?(!@.sub && @.short_id)]");
					angular.forEach(_selected_categories, function(_cat) {
						$scope.tag.categories.push(_cat.short_id);
					});
				}
			});
		}, 0);

	}, 0);

	$scope.$watch("tag.type", function(_new, _old) {
		if(_new !== _old && $scope.type_watch_paused === false) {

			$scope.pending_dialog_type = true;

			var modalInstance = $modal.open({
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

			modalInstance.result.then(function() {
				$scope.tag.options = [];
			}, function() {
				$scope.type_watch_paused = true;
				$scope.tag.type = _old;
				$timeout(function() {
					$scope.type_watch_paused = false;
				}, 0);
			}).finally(function() {
				$scope.pending_dialog_type = false;
			});
		}
	});

	$scope.edit_dropdown_option = function(index) {
		var option = index === -1 ? $tag_util.newDropdownOption() : $scope.tag.options[index];

		var modalInstance = $modal.open({
			templateUrl: 'template/modal/tag/create_new.html',
			controller: 'create_newCtrl',
			resolve: {
				data: function () {
					return {
						langs: _langs,
						option: option,
						options: $scope.tag.options
					};
				}
			}
		});

		modalInstance.result.then(function(data) {
			$scope.tag.options.push(data.option);
		}, function () {
			//Cancel
		});
	};

	$scope.create_freetext_option = function() {
		$scope.new_option["metric_type"] = $scope.selected_mu[0].value;
		$scope.new_option.type = parseInt($scope.freetext_type, 10);

		$scope.tag.options.push($scope.new_option);

		$scope.new_option = {};
	};

	$scope.delete_option = function(index) {
		$scope.tag.options.splice(index, 1);
	};

	$scope.cancel = function() {
		$scope.type_watch_paused = true;
		$scope.tag = angular.copy($scope._shadow);

		$timeout(function() {
			$scope.type_watch_paused = false;
		}, 0);
	};

	$scope.save = function() {
		$tag.modify("POST", $scope.tag).then(function() {
			toastr.success("Tag saved successfully!");
		}, function(err) {
			toastr.error("Failed saving tag!", err);
		});
	};

	$scope._shadow = angular.copy($scope.tag);
}]);

todevise.controller("create_newCtrl", function($scope, $modalInstance, data) {
	$scope.data = data;

	$scope.ok = function() {
		$modalInstance.close({
			"option": $scope.data.option
		});
	};

	$scope.cancel =  function() {
		$modalInstance.dismiss();
	};
});
