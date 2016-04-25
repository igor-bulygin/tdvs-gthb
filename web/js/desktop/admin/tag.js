var todevise = angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'angular-multi-select', 'global-admin', 'global-desktop', 'api', 'angular-sortable-view', 'ui.validate']);

todevise.controller('tagCtrl', ["$scope", "$timeout", "$tag", "$tag_util", "$category_util", "toastr", "$uibModal", "$cacheFactory", function($scope, $timeout, $tag, $tag_util, $category_util, toastr, $uibModal, $cacheFactory) {
	$scope.lang = _lang;
	$scope.tag = _tag;
	$scope.mus = _mus;
	$scope.selected_mu = "";

	$scope.c_mus = $cacheFactory("mus");
	angular.forEach(_mus, function(mus) {
		$scope.c_mus.put(mus.value, mus);
	});

	$scope.pending_dialog_type = false;
	$scope.type_watch_paused = false;

	//Sort by path length
	$scope.categories = $category_util.create_tree(_categories);

	$scope.$watch("tag.stock_and_price", function(_new, _old) {
		if (_new === true) {
			$scope.tag.required = true;
		}
	});

	$scope.$watch("tag.required", function(_new, _old) {
		if (_new === false && $scope.tag.stock_and_price === true) {
			$scope.tag.required = true;
		}
	});

	$scope.$watch("tag.type", function(_new, _old) {
		if(_new !== _old && $scope.type_watch_paused === false) {

			$scope.pending_dialog_type = true;

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

	$scope.get_mu_type = function(v) {
		var _type = $scope.c_mus.get(v);
		if(_type !== undefined) {
			return _type.text;
		}
	};

	$scope.get_input_type = function(v) {
		return _tagoption_txt[v];
	};

	$scope.edit_dropdown_option = function(index) {
		var _mod_option_index;
		if(index === -1) {
			$scope.tag.options.push( $tag_util.newDropdownOption() );
			_mod_option_index = $scope.tag.options.length - 1;
		} else {
			_mod_option_index = index;
		}

		var modalInstance = $uibModal.open({
			templateUrl: 'template/modal/tag/create_new.html',
			controller: 'create_newCtrl',
			resolve: {
				data: function () {
					return {
						langs: _langs,
						index: _mod_option_index,
						options: $scope.tag.options
					};
				}
			}
		});

		modalInstance.result.then(function(data) {
			$scope.tag.options = data.options;
		}, function () {
			// Remove the new, empty option we created (don't do anything if we were editing an option)
			if(index === -1) {
				$scope.tag.options.pop();
			}
		});
	};

	$scope.create_freetext_option = function() {
		$scope.new_option["metric_type"] = $scope.selected_mu;
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

todevise.controller("create_newCtrl", function($scope, $uibModalInstance, data) {
	$scope.data = data;
	$scope.colors = _colors;

	$scope.colors_lookup = {};
	angular.forEach(_colors, function(color) {
		$scope.colors_lookup[color.value] = color;
	});

	$scope.get_color_from_value = function(value) {
		if($scope.colors_lookup.hasOwnProperty(value)) {
			return $scope.colors_lookup[value];
		} else {
			return {};
		}
	};

	$scope.is_duplicated = function(value) {
		var i = 0;
		angular.forEach(data.options, function(option) {
			if(option.value === value) i++;
		});
		return i === 1;
	};

	$scope.ok = function() {
		$uibModalInstance.close({
			"options": $scope.data.options
		});
	};

	$scope.cancel =  function() {
		$uibModalInstance.dismiss();
	};
});
