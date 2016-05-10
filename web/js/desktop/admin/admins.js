var todevise = angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'global-admin', 'global-desktop', 'api']);

todevise.controller('adminsCtrl', ["$scope", "$timeout", "$admin", "$admin_util", "toastr", "$uibModal", "$compile", "$http", function($scope, $timeout, $admin, $admin_util, toastr, $uibModal, $compile, $http) {

	$scope.renderPartial = function() {
		$http.get(aus.syncToURL()).success(function(data, status) {
			angular.element('.body-content').html( $compile(data)($scope) );
		}).error(function(data, status) {
			toastr.error("Failed to refresh content!");
		});
	};

	$scope.delete = function(admin_id) {
		var modalInstance = $uibModal.open({
			templateUrl: 'template/modal/confirm.html',
			controller: 'confirmCtrl',
			resolve: {
				data: function () {
					return {
						title: "Are you sure?",
						text: "You are about to delete an admin!"
					};
				}
			}
		});

		modalInstance.result.then(function() {
			$admin.get({ short_id: admin_id }).then(function(admin) {
				if (admin.length !== 1) return;
				admin = admin.shift();

				$admin.delete(admin).then(function(data) {
					toastr.success("Admin deleted!");
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
			var admin = $admin_util.newAdmin(data.type, data.name, data.surnames, data.email, data.password);
			$admin.modify("POST", admin).then(function(deviser) {
				toastr.success("Admin created!");
				$scope.renderPartial();
			}, function(err) {
				toastr.error("Couldn't create admin!", err);
			});
		}, function () {
			//Cancel
		});
	};


}]);

todevise.controller("create_newCtrl", function($scope, $uibModalInstance, data) {
	$scope.data = data;
	$scope.surnames = [];

	$scope.ok = function() {
		var _surnames = $scope.surnames.map(function(v){
			return v.value;
		});

		$uibModalInstance.close({
			"type": [_ADMIN],
			"name": $scope.name,
			"surnames": _surnames,
			"email": $scope.email,
			"password": $scope.password
		});
	};

	$scope.cancel =  function() {
		$uibModalInstance.dismiss();
	};
});
