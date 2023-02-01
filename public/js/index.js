$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //select all functionality
    $(document).on('change', '.record__select', function () {
        getSelectedRecords();
    });

    // used to select all records
    $(document).on('change', '#record__select-all', function () {

        $('.record__select').prop('checked', this.checked);
        getSelectedRecords();
    });

    function getSelectedRecords() {
        var recordIds = [];

        $.each($(".record__select:checked"), function () {
            recordIds.push($(this).val());
        });

        $('#record-ids').val(JSON.stringify(recordIds));

        recordIds.length > 0
            ? $('#bulk-delete').attr('disabled', false)
            : $('#bulk-delete').attr('disabled', true)

    }

    $(document).on('change', '.load-image', function (e) {

        var that = $(this);

        let reader = new FileReader();
        reader.onload = function () {
            that.parent().find('.loaded-image').attr('src', reader.result);
            that.parent().find('.loaded-image').css('display', 'block');
        }
        reader.readAsDataURL(e.target.files[0]);

    });

    $(document).on('submit', '.modal-body form', function (e) {
        e.preventDefault();

        $('.errors').hide();
        $('.errors').empty();

        let url = $(this).attr('action');
        let data = new FormData(this);

        let button = $('button[type="submit"]', this);
        let loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i>';
        let originalText = button.html();

        button.html(loadingText);

        $.ajax({
            url: url,
            data: data,
            method: "post",
            processData: false,
            contentType: false,
            success: function (response) {

                $('.modal').modal('hide');
                $('.modal-body form')[0].reset();
                $('.datatable').DataTable().ajax.reload();
                $(".select2").val('').trigger('change');

                console.log(response);

                new Noty({
                    layout: 'topRight',
                    type: 'alert',
                    text: response,
                    killer: true,
                    timeout: 5000,
                }).show();

                button.html(originalText);
            },
            error: function (xhr, status, error) {

                $('.errors').show();
                let errors = JSON.parse(xhr.responseText)['errors'];

                $.each(errors, function (key, val) {
                    let html = '<p class="mb-0">' + val[0] + '</p>';
                    $('.errors').append(html);
                });

                button.html(originalText);
            }
        });//end of ajax call
        // $('.datatable').reload()
    });

    $(document).on('click', '.remote-data', function (e) {
        e.preventDefault();

        let url = $(this).attr('href');
        let title = $(this).data('title');

        $('.general-modal').modal('show');
        $('.general-modal .modal-title').empty().html(title);
        $('.general-modal .modal-body').empty().append('<div class="d-flex justify-content-center align-items-center"><div class="loader"></div></div>');

        $.ajax({
            url: url,
            success: function (html) {
                $('.general-modal .modal-body').empty().append(html);
            },

        });//end of ajax call

    })

    // $(document).on('submit', '.form-edit', function (e) {
    //     e.preventDefault();
    //
    //     $('.errors').hide();
    //     $('.errors').empty();
    //
    //     let url = $(this).attr('action');
    //     let data = new FormData(this);
    //
    //     let button = $('.form-edit button[type=submit]');
    //     let loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i>';
    //     let originalText = button.html();
    //     button.html(loadingText);
    //
    //     $.ajax({
    //         url: url,
    //         data: data,
    //         method: "post",
    //         processData: false,
    //         contentType: false,
    //         success: function (response) {
    //
    //             $('.modal').modal('hide');
    //             $('.')[0].reset();
    //             $('.datatable').DataTable().ajax.reload();
    //             $(".select2").val('').trigger('change');
    //
    //             new Noty({
    //                 layout: 'topRight',
    //                 type: 'alert',
    //                 text: response,
    //                 killer: true,
    //                 timeout: 2000,
    //             }).show();
    //
    //             button.html(originalText);
    //         },
    //         error: function (xhr, status, error) {
    //
    //             $('.errors').show();
    //             let errors = JSON.parse(xhr.responseText)['errors'];
    //
    //             $.each(errors, function (key, val) {
    //                 let html = '<p class="mb-0">' + val[0] + '</p>';
    //                 $('.errors').append(html);
    //             });
    //
    //             button.html(originalText);
    //         }
    //     });//end of ajax call
    //
    // });

    $('.modal').on('hidden.bs.modal', function () {
        $('.errors').empty();
        $('.errors').hide();
        $('.modal form')[0].reset();
        $(".select2").val('').trigger('change');
        $('.loaded-image').css('display', 'none');
    })

});//end of document ready
//
// function toggleDisabled() {
//
//     $('form div').each(function () {
//         if ($(this).is(':hidden')) {
//             $(this).children().find('input').attr('disabled', true)
//         } else {
//             $(this).children().find('input').attr('disabled', false)
//         }
//
//     });
//
// }//end of checkInput
