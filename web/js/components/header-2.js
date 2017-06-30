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
	})
	/*
    $('.toggle-category').on('mouseover', function (e) {
        $('.cathegory-menu').each(function() {
            $(this).hide();
        });
        $($(this).data('target')).show();
    });
    $('.menu-cathegories').on('mouseout', function(e) {
		$('.cathegory-menu').each(function() {
			$(this).hide();
		});
    });
    */
});