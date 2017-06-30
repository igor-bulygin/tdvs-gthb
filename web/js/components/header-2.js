/*
 * Show or hide subcategories on hover on main categories
 */
$(function () {
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
});