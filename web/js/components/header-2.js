/*
 * Show or hide subcategories on hover on main categories
 */
$(function () {
    $('.toggle-category').on('mouseover', function (e) {
        $('ul.category').each(function() {
            $(this).removeClass('active');
        });
        $($(this).data('target')).addClass('active');
    });
});