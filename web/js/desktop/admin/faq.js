var todevise = angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'ngJsTree', 'global-admin', 'global-desktop', 'api']);

todevise.controller('faqCtrl', ["$scope", "$faq", "$category_util", "$location", "toastr", "$uibModal", function($scope, $faq, $category_util, $location, toastr, $uibModal) {

	$scope.treeData = [];
	$scope.langs = _langs;
	$scope.oneAtATime = false;
	$scope.faq_id = _faq_id;
	$scope.faq_subid = _faq_subid;
	$scope.faq={};

	console.log(_faq_id);
	console.log(_faq_subid);


	$scope.load = function () {

		$faq.get({
			"short_id": $scope.faq_id
		}).then(function(_faq) {
			if(_faq.length !== 1) {
				toastr.error("Unexpected category details!");
				return;
			} else {
				_faq = _faq.pop();
				console.log(_faq);

				$scope.faq = _faq;
				if($scope.faq_subid.length <= 0){
					$scope.faq_subid = $scope.faq.faqs.push({answer: {}, question: {}});
					$scope.faq_subid = $scope.faq_subid - 1;
				}
				$scope.subfaq = $scope.faq.faqs[$scope.faq_subid];

				console.log($scope.subfaq);
			}

		}, function(err) {
			toastr.error("Couldn't get faqs details!", err);
		});
	};


	$scope.load();


	$scope.save = function(){

		$scope.faq.faqs[$scope.faq_subid] = $scope.subfaq;

		$faq.modify("POST", $scope.faq).then(function() {
			toastr.success("Faq modified!");

			//var _node = $category_util.categoryToNode(data.category);
			//var _current = _.findWhere($scope.treeData, {id: data.category.short_id});
			//angular.merge(_current, _node);

		}, function(err) {
			toastr.error("Couldn't modify category!", err);
		});


	};


	$scope.groups = [
		{
			title: 'Dynamic Group Header - 1',
			content: 'Dynamic Group Body - 1'
		},
		{
			title: 'Dynamic Group Header - 2',
			content: 'Dynamic Group Body - 2'
		}
	];

	$scope.status = {
		isCustomHeaderOpen: false,
		isFirstOpen: true,
		isFirstDisabled: false
	};


	$scope.create_sub = function(node_id, node, action_id, action_el) {
		var path = "/";
		if (node !== undefined) {
			path += $scope.treeInstance.jstree(true).get_path(node, "/", true) + "/";
		}

		$faq.modify("POST", tmp_node).then(function(faqs_group) {
			toastr.success("Category created!");
			console.log("debug id:");
			console.log(faqs_group);
			$scope.treeData.push(
				{
					id: faqs_group.short_id,
					parent: '#',
					text: faqs_group.title[_lang] || "",
					icon: "glyphicon glyphicon-menu-hamburger"
				}
			);
		}, function(err) {
			toastr.error("Couldn't create category!", err);
		});
	};









}]);
