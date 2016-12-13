/*
 * Show or hide subcategories on hover on main categories
 */
$(function () {
    $('.toggle-category').on('hover', function (e) {
        $('ul.category').each(function() {
            $(this).removeClass('active');
        });
        $($(this).data('target')).addClass('active');
    });
});