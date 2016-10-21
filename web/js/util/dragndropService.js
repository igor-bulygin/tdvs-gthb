(function () {
	"use strict";

	function dragndropService() {

		this.dragStart = dragStart;
		this.dragOver = dragOver;
		this.moved = moved;
		this.canceled = canceled;

		function dragStart(index, item_collection) {
			this.original_index = index;
			this.original_items = item_collection;
			this.item_being_moved = item_collection[index];
		}

		function dragOver(index, item_collection) {
			//copy original items
			item_collection = angular.copy(this.original_items);
			//get index where it will drop
			this.previous_index = index;
			//if position is after original index, insert
			if(this.previous_index > this.original_index) {
				item_collection.splice(this.previous_index, 0, this.item_being_moved);
			} else {
				//if not, change item in original index to the image before it and then add item being moved
				item_collection[this.original_index] = this.original_items[this.original_index-1];
				item_collection.splice(this.previous_index, 0, this.item_being_moved);
			}
			return item_collection;
		}

		function moved(item_collection) {
			item_collection = angular.copy(this.original_items);
			if(this.previous_index > this.original_index) {
				item_collection.splice(this.previous_index, 0, this.item_being_moved);
				item_collection.splice(this.original_index, 1);
			} else {
				item_collection.splice(this.original_index, 1);
				item_collection.splice(this.previous_index, 0, this.item_being_moved);
			}
			//reset iteration
			delete this.item_being_moved;
			delete this.previous_index;
			return item_collection;
		}

		function canceled() {
			return this.original_items;
		}



	}

	angular.module('util')
		.service('dragndropService', dragndropService);
}());