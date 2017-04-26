(function () {
	"use strict";

	function controller(productDataService, UtilService) {
		var vm = this;
		vm.person_id = person.short_id;
		vm.getDeviserWorks = getDeviserWorks;
		vm.setWork = setWork;
		vm.findWorkInItems = findWorkInItems;
		vm.works_helper = [];

		function getDeviserWorks(id) {
			function onGetWorksSuccess(data) {
				vm.works = angular.copy(data.items);
			}
			var params = {
				deviser: id
			}
			productDataService.getProductPub(params, onGetWorksSuccess, UtilService.onError);
		}

		function findWorkInItems(id) {
			return vm.component.items.findIndex(function(item) {
				if(item.work)
					return item.work === id;
			})
		}

		function setWork(work) {
			if(!angular.isArray(vm.component.items))
				vm.component['items'] = []
			var position = findWorkInItems(work.id);
			if(position < 0) {
				vm.component.items.push({
					position: vm.component.items.length,
					work: work.id
				})
				vm.works_helper.push(work)
			} else {
				vm.component.items.splice(position, 1);
				vm.works_helper.splice(position, 1);
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