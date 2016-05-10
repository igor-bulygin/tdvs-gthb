var todevise = angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'angular-multi-select', 'global-admin', 'global-desktop', 'api']);

todevise.controller('devisersCtrl', ["$scope", "$timeout", "$deviser", "$deviser_util", "toastr", "$uibModal", "$compile", "$http", function($scope, $timeout, $deviser, $deviser_util, toastr, $uibModal, $compile, $http) {

	$scope.renderPartial = function() {
		$http.get(aus.syncToURL()).success(function(data, status) {
			angular.element('.body-content').html( $compile(data)($scope) );
		}).error(function(data, status) {
			toastr.error("Failed to refresh content!");
		});
	};

	$scope.delete = function(deviser_id) {
		var modalInstance = $uibModal.open({
			templateUrl: 'template/modal/confirm.html',
			controller: 'confirmCtrl',
			resolve: {
				data: function () {
					return {
						title: "Are you sure?",
						text: "You are about to delete a deviser!"
					};
				}
			}
		});

		modalInstance.result.then(function() {
			$deviser.get({ short_id: deviser_id }).then(function(deviser) {
				return;
				if (deviser.length !== 1) return;
				deviser = deviser.shift();

				$deviser.delete(deviser).then(function(data) {
					toastr.success("Deviser deleted!");
					$scope.renderPartial();
				}, function(err) {
					toastr.error("Couldn't delete tag!", err);
				});
			}, function(err) {
				toastr.error("Couldn't find deviser!", err);
			});
		}, function() {
			//Cancel
		});
	};

	$scope.create_new = function() {
		var modalInstance = $uibModal.open({
			templateUrl: 'template/modal/tag/create_new.html',
			controller: 'create_newCtrl',
			resolve: {
				data: function () {
					return {};
				}
			}
		});

		modalInstance.result.then(function(data) {
			var deviser = $deviser_util.newDeviser(data.type, data.name, data.surnames, data.email, data.country, data.slug);
			$deviser.modify("POST", deviser).then(function(deviser) {
				toastr.success("Deviser created!");
				$scope.renderPartial();
			}, function(err) {
				toastr.error("Couldn't create tag!", err);
			});
		}, function () {
			//Cancel
		});
	};


}]);

todevise.controller("create_newCtrl", function($scope, $uibModalInstance, data) {
	$scope.data = data;
	$scope.surnames = [];
	$scope.selected_country = {};

	$scope.ok = function() {
		var _surnames = $scope.surnames.map(function(v){
			return v.value;
		});
		var _country = $scope.selected_country.pop();

		$uibModalInstance.close({
			"type": [_DEVISER],
			"name": $scope.name,
			"surnames": _surnames,
			"email": $scope.email,
			"country": _country.country_code,
			"slug": $scope.slug
		});
	};

	$scope.cancel =  function() {
		$uibModalInstance.dismiss();
	};
});
