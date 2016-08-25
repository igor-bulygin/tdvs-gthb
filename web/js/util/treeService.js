/*
 * ngjstree service for common config and functions
 */

(function () {
	"use strict";

	function treeService() {

		this.treeDefaultConfig = function (key, vm) {
			return {
				core: {
					check_callback: true,
					themes: {
						name: "default-dark"
					}
				},
				state: {
					key: key,
					filter: function (state) {
						vm.tree_state = {};
						angular.copy(state, vm.tree_state)
					}
				},
				dnd: {
					touch: "selected"
				},
				search: {
					fuzzy: false,
					case_insensitive: true,
					show_only_matches: true,
					search_only_leaves: false
				},
				plugins: ["state", "dnd", "search", "sort", "wholerow", "actions", "types"]
			}
		};

	}

	angular.module('util')
		.service('treeService', treeService);
}());