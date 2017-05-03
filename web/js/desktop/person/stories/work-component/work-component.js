(function () {
	"use strict";

	function controller(productDataService, UtilService, $scope) {
		var vm = this;
		vm.person_id = person.short_id;
		vm.getDeviserWorks = getDeviserWorks;
		vm.setWork = setWork;
		vm.findWorkInItems = findWorkInItems;
		vm.works_helper = [];

		init();

		function init() {
			if(!angular.isArray(vm.component.items))
				vm.component['items'] = []
			else if (vm.component.items.length > 0) {
				vm.component.items.forEach(function(work) {
					getProduct(work.work, work.position);
				});
			}
		}

		function getDeviserWorks(id) {
			function onGetWorksSuccess(data) {
				vm.works = angular.copy(data.items);
			}
			var params = {
				deviser: id
			}
			productDataService.getProductPub(params, onGetWorksSuccess, UtilService.onError);
		}

		function getProduct(id, position) {
			function onGetProductSuccess(data) {
				addWorkToHelper(data);
			}
			productDataService.getProductPub({
				idProduct: id
			}, onGetProductSuccess, UtilService.onError);
		}

		function findWorkInItems(id) {
			if(vm.works_helper.length > 0) {
				return vm.works_helper.findIndex(function(item) {
					if(item.id)
						return item.id === id;
				})
			} else {
				return -1;
			}
		}

		function addWorkToHelper(work) {
			vm.works_helper.push(work);
		}

		function deleteWorkFromHelper(pos) {
			vm.works_helper.splice(pos, 1);
		}

		function setWork(work) {
			var position = findWorkInItems(work.id);
			if(position < 0) {
				addWorkToHelper(work);
			} else {
				deleteWorkFromHelper(position);
			}
		}

		$scope.$watch('storyWorkComponentCtrl.works_helper', function(newValue, oldValue) {
			vm.component.items = newValue.map(function(element, index) {
				return Object.assign({}, {work: element.id, position: index+1})
			})
		}, true)

	}

	var component = {
		templateUrl: currentHost() + "/js/desktop/person/stories/work-component/work-component.html",
		controller: controller,
		controllerAs: "storyWorkComponentCtrl",
		bindings: {
			component: '<',
			devisers: '<'
		}
	}

	angular
		.module('todevise')
		.component('storyWorkComponent', component);
}());