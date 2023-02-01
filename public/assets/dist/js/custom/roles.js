$(function () {

    $(document).on('change', '.all-roles', function () {

        $(this).parents('tr').find('input[type="checkbox"]').prop('checked', this.checked);

    });

    $(document).on('change', '.role', function () {

        if (!this.checked) {
            $(this).parents('tr').find('.all-roles').prop('checked', this.checked);
        }

    });

});//end of document ready
