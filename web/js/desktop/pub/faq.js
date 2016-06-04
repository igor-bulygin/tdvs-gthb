var todevise = angular.module('todevise', []);

/*
 * This manage show/hide text on faq section
 */


 //TODO: $cacheFactory needed?
todevise.controller('faqCtrl', ['$scope', '$cacheFactory', function ($scope, $cacheFactory) {

	$scope.answersAndQuestions = _answersAndQuestions;

	console.log(_answersAndQuestions);
	console.log($scope.anwersAndQuestions);
}]);




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
