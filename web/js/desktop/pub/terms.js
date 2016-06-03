var todevise = angular.module('todevise', []);

/*
 * This manage show/hide text on terms section
 */

$(function() {
	function reset() {
		$(".text-content").hide();
		$(".text-content").first().show();
	}

	$(".title-content").click(function(event){
		$(".text-content").hide();
		$(this).next(".text-content").show();
	});

	$(".menu-entry").click(function(event){
		$(".text-content").hide();
		var text_id=$(this).attr("id");
		$("."+text_id).show();
	});

	reset();

});
