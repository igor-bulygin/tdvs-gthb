(function () {

	function controller($scope, $product, $product_util, $uibModal, toastr, $http, $compile) {
		var vm = this;

		vm.deviser = _deviser;
		vm.renderPartial = renderPartial;
		vm.new_product = new_product;
		vm.delete_product = delete_product;


		function renderPartial() {
			$http.get(aus.syncToURL()).success(function (data, status) {
				angular.element('.body-content').html($compile(data)($scope));
			}).error(function (data, status) {
				toastr.error("Failed to refresh content!");
			});
		}

		function new_product() {
			var _product = $product_util.newProduct(vm.deviser.short_id);
			$product.modify("POST", _product).then(function (data) {
				window.location.href = window.location.origin + "/" + vm.deviser.slug + "/edit-work/" + data.short_id;
			}, function (err) {
				toastr.error("Couldn't create product!", err);
			});
		}

		function delete_product(product_id) {
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

			modalInstance.result.then(function () {
				$product.get({
					short_id: product_id
				}).then(function (product) {
					if (product.length !== 1) return;
					product = product.shift();

					$product.delete(product).then(function (data) {
						toastr.success("Product deleted!");
						vm.renderPartial();
					}, function (err) {
						toastr.error("Couldn't delete product!", err);
					});
				}, function (err) {
					toastr.error("Couldn't find product!", err);
				});
			}, function () {
				//Cancel
			});
		}

	}

	angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'global-admin', 'global-desktop', 'api'])
		.controller('productsCtrl', controller);


}());