var todevise = angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'ngJsTree', 'global-admin', 'global-desktop', 'api']);

todevise.controller('termCtrl', ["$scope", "$term", "$category_util", "$location", "toastr", "$uibModal", function($scope, $term, $category_util, $location, toastr, $uibModal) {

	$scope.treeData = [];
	$scope.langs = _langs;
	$scope.oneAtATime = false;
	$scope.term_id = _term_id;
	$scope.term_subid = _term_subid;
	$scope.term={};

	console.log(_term_id);
	console.log(_term_subid);


	$scope.load = function () {

		$term.get({
			"short_id": $scope.term_id
		}).then(function(_term) {
			if(_term.length !== 1) {
				console.log(_term);
				toastr.error("Unexpected category details!");
				return;
			} else {
				_term = _term.pop();
				console.log(_term);

				$scope.term = _term;
				if($scope.term_subid.length <= 0){
					$scope.term_subid = $scope.term.terms.push({answer: {}, question: {}});
					$scope.term_subid = $scope.term_subid - 1;
				}
				$scope.subterm = $scope.term.terms[$scope.term_subid];

				console.log($scope.subterm);
			}

		}, function(err) {
			toastr.error("Couldn't get terms details!", err);
		});
	};


	$scope.load();


	$scope.save = function(){

		$scope.term.terms[$scope.term_subid] = $scope.subterm;

		$term.modify("POST", $scope.term).then(function() {
			toastr.success("term modified!");

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

		$term.modify("POST", tmp_node).then(function(terms_group) {
			toastr.success("Category created!");
			console.log("debug id:");
			console.log(terms_group);
			$scope.treeData.push(
				{
					id: terms_group.short_id,
					parent: '#',
					text: terms_group.title[_lang] || "",
					icon: "glyphicon glyphicon-menu-hamburger"
				}
			);
		}, function(err) {
			toastr.error("Couldn't create category!", err);
		});
	};









}]);
