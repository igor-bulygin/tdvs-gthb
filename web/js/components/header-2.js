/*
 * Show or hide subcategories on hover on main categories
 */
$(function () {
    $('ul.category').hide();
    $('ul.category.active').show();
    $('.toggle-category').on('hover', function (e) {
        $('ul.category').each(function() {
            $(this).removeClass('.active').hide();
        });
        $($(this).data('target')).addClass('.active').show();
    });
});