$(document).ready(function() {

    if ($('#import_source').val() == 'magento') {
        $('#import_url').show();
        if ($('#import_url').val() == '') {
            $('.no-url-alert').show();
        }
        else {
            $('.no-url-alert').hide();
        }
    }


    $('#import_source').on('change', function () {
        if ($(this).val() == 'magento') {
            $('#import_url').show(400);
        }
        else {
            $('#import_url').hide(400);
        }
    });

    $('#source_url').on('change', function () {
        if ($(this).val() == '') {
            $('.no-url-alert').show();
        }
        else {
            $('.no-url-alert').hide();
        }
    });


    $('#submit-form').on('click', function (e) {

        e.preventDefault();

        if ($('#import_source').val() == 'magento') {
            if ($('#source_url').val() == '') {
                if ($('.no-url-alert').is(':visible')) {
                    $('#import_form').submit();
                }
                else {
                    $('.no-url-alert').show();
                }
            }
            else {
                $('#import_form').submit();
            }
        }
        else {
            $('#import_form').submit();
        }
    });

});