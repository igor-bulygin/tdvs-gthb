(function () {
	"use strict";

	function controller(productDataService, UtilService) {
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
				addWorkToHelper(data, position);
			}
			productDataService.getProductPub({
				idProduct: id
			}, onGetProductSuccess, UtilService.onError);
		}

		function findWorkInItems(id) {
			return vm.component.items.findIndex(function(item) {
				if(item.work)
					return item.work === id;
			})
		}

		function addWorkToHelper(work, position) {
			vm.works_helper[position] = work;
		}

		function deleteWorkFromHelper(pos) {
			vm.works_helper.splice(pos, 1);
		}

		function setWork(work) {
			var position = findWorkInItems(work.id);
			if(position < 0) {
				vm.component.items.push({
					position: vm.component.items.length,
					work: work.id
				})
				addWorkToHelper(work, vm.component.items.length-1);
			} else {
				vm.component.items.splice(position, 1);
				deleteWorkFromHelper(position);
				vm.component.items = UtilService.setElementPosition(vm.component.items);
			}
		}

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