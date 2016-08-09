/*
 * This manage show/hide text on faq section
 */

(function () {
	"use strict";

	//TODO: $cacheFactory needed?
	function faqCtrl($cacheFactory) {
		var vm = this;

		function init() {
			vm.groupOfFaqs = _groupOfFaqs;
			vm.activeFaqId = vm.groupOfFaqs[0].short_id;
		}

		init();

		vm.showFaqs = function (activeFaqId) {
			vm.activeFaqId = activeFaqId;
		}

	}

	angular.module('todevise', [])
		.controller('faqCtrl', faqCtrl);

}());

// $(function() {
// 	function reset() {
// 		$(".answer").hide();
// 		$(".answer").first().show();
// 		childrenIconToMinus($(".question-content").first())
// 	}
// 	function allIconsPlus() {
// 		$(".glyphicon-minus-sign")
// 			.removeClass("glyphicon-minus-sign")
// 			.addClass("glyphicon-plus-sign");
// 	}
//
// 	function childrenIconToMinus(classparent){
// 		$(classparent).children(".glyphicon")
// 			.removeClass("glyphicon-plus-sign")
// 			.addClass("glyphicon-minus-sign");
// 	}
//
// 	$(".question-content").click(function(event){
// 		$(".answer").hide();
// 		//remember, next works because .question-content and
// 		//.answers have the same level on doom
// 		$(this).next(".answer").show();
// 		allIconsPlus();
// 		childrenIconToMinus(this);
// 	});
//
// 	$(".menu-entry").click(function(event){
// 		$(".answer").hide();
// 		var answer_id=$(this).attr("id");
// 		$("."+answer_id).show();
// 		allIconsPlus();
// 		childrenIconToMinus($("."+answer_id).prev(".question-content"));
// 	});
//
// 	reset();
//
// });