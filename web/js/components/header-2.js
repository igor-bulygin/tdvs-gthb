/*
 * Show or hide subcategories on hover on main categories
 */
$(function () {
	$('.hover-toggle').each(function() {
		let group = $(this).data('group');
		let target = $(this).data('target');
		$(this).on('mouseover', function() {
			$(group).removeClass('active');
			$(target).addClass('active');
		});
	});
	$('#navbar-wrapper').on('mouseleave', function(e) {
		// hide all submenus
		$('.menu-categories').removeClass('active');
	});
});