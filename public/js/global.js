(function ($) {
    'use strict';
    /*==================================================================
        [ Daterangepicker ]*/
    try {
        $('.js-datepicker').daterangepicker({
            "singleDatePicker": true,
            "showDropdowns": true,
            "autoUpdateInput": false,
            locale: {
                format: 'YYYY-MM-DD'
            },
        });

        var myCalendar = $('.js-datepicker');
        var isClick = 0;

        $(window).on('click', function () {
            isClick = 0;
        });

        $(myCalendar).on('apply.daterangepicker', function (ev, picker) {
            isClick = 0;
            $(this).val(picker.startDate.format('YYYY-MM-DD'));

        });

        $('.js-btn-calendar').on('click', function (e) {
            e.stopPropagation();

            if (isClick === 1) isClick = 0;
            else if (isClick === 0) isClick = 1;

            if (isClick === 1) {
                myCalendar.focus();
            }
        });

        $(myCalendar).on('click', function (e) {
            e.stopPropagation();
            isClick = 1;
        });

        $('.daterangepicker').on('click', function (e) {
            e.stopPropagation();
        });


    } catch (er) {
        console.log(er);
    }
    /*[ Select 2 Config ]
        ===========================================================*/

    try {
        var selectSimple = $('.js-select-simple');

        selectSimple.each(function () {
            var that = $(this);
            var selectBox = that.find('select');
            var selectDropdown = that.find('.select-dropdown');
            selectBox.select2({
                dropdownParent: selectDropdown
            });
        });

    } catch (err) {
        console.log(err);
    }


})(jQuery);

/*
 * Function for showing validation messages in bootstarp modal
 * error: json error messages $form: form ID
 * */
function associate_errors(errors, $form, multimodal) {
    console.log(errors);
    var $group;
    $form.find('.form-group').removeClass('has-error').find('.help-block').text('');
    $.each(errors, function (key, value) {
        if (null != multimodal && multimodal) {
            key = key.replace('.', '_');
        }
        $group = $form.find("[id='" + key + "']");
        $group.addClass('has-error').find('.help-block').text(value[0]);
    });
    if ($group.length > 0) {
        $('html, body').animate({
            scrollTop: $(".has-error").offset().top - 120
        }, 1000);
    }
}

function formSubmit($form, url, table, e, message,returnUrl = null) {
    var $form = $form;
    var url = url;
    var e = e;
    var table = table;
    var formData = new FormData($form[0]);
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        type: 'POST',
        data: formData,
        success: function (data) {
            if (data.success) {
                swal("Saved", message, "success").then((value) => {
                    $("#myModal").modal('hide');
                    if(returnUrl){
                        window.location.href = returnUrl;
                    }else{
                        table.ajax.reload();
                    }

                })

            } else {
                swal("Wanning", data.error, "wanning");
                console.log(data);
            }
        },
        fail: function (response) {
            console.log('Unknown error');
        },
        error: function (xhr, textStatus, thrownError) {
            associate_errors(xhr.responseJSON.errors, $form);
        },
        contentType: false,
        processData: false,
    });
}

function deleteRecord(url, table, message,returnUrl = null) {
    var url = url;
    var table = table;
    swal({
            title: "Are you sure?",
            text: "You will not be able to undo this action. Proceed?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data.success) {
                            swal("Deleted", message, "success");
                            if(returnUrl){
                                window.location.href = returnUrl;
                            }else{
                                if (table != null) {
                                    table.ajax.reload();
                                }
                            }
                        } else if (data.success == false) {
                            if (Object.prototype.hasOwnProperty.call(data, 'message') && data
                                .message) {
                                swal("Warning", data.message, "warning");
                            } else {
                                swal("Warning", 'Data exists', "warning");
                            }
                        } else if (data.warning == true) {
                            swal("Warning", data.message, "warning");
                        } else {
                            console.log(data);
                        }
                    },
                    error: function (xhr, textStatus, thrownError) {
                        console.log(xhr.status);
                        console.log(thrownError);
                    },
                    contentType: false,
                    processData: false,
                });

            } else {
                // swal("Your imaginary file is safe!");
            }
        });

}
