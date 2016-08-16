var todevise = angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'global-admin', 'global-desktop', 'api']);

todevise.controller('productsCtrl', ["$scope", "$product", "$product_util", "$uibModal", "toastr", "$http", "$compile", function($scope, $product, $product_util, $uibModal, toastr, $http, $compile) {
	$scope.deviser = _deviser;

	$scope.renderPartial = function() {
		$http.get(aus.syncToURL()).success(function(data, status) {
			angular.element('.body-content').html( $compile(data)($scope) );
		}).error(function(data, status) {
			toastr.error("Failed to refresh content!");
		});
	};

	$scope.new_product = function() {
		var _product = $product_util.newProduct($scope.deviser.short_id);
		$product.modify("POST", _product).then(function(data) {
			window.location.href = window.location.origin + "/" + $scope.deviser.slug + "/edit-work/" + data.short_id;
		}, function(err) {
			toastr.error("Couldn't create product!", err);
		});
	};

	$scope.delete = function (product_id) {
		var modalInstance = $uibModal.open({
			templateUrl: 'template/modal/confirm.html',
			controller: 'confirmCtrl',
			resolve: {
				data: function () {
					return {
						title: "Are you sure?",
						text: "You are about to delete a product!"
					};
				}
			}
		});

		modalInstance.result.then(function() {
			$product.get({ short_id: product_id }).then(function(product) {
				if (product.length !== 1) return;
				product = product.shift();

				$product.delete(product).then(function(data) {
					toastr.success("Product deleted!");
					$scope.renderPartial();
				}, function(err) {
					toastr.error("Couldn't delete product!", err);
				});
			}, function(err) {
				toastr.error("Couldn't find product!", err);
			});
		}, function() {
			//Cancel
		});
	}
}]);
