jQuery(document).ready(function($) {
    var form = $("#woo-customer-form");
    // form.on('submit', function(e) {
    //     e.preventDefault();
    //     var form = $(this);
    //     var data: form.serialize();
    //     $.ajax({
    //         url: woo_customer_params.url,
    //         data: {
    //             action: 'woo_customer_form_callback',
    //             nonce: woo_customer_params.nonce,
    //             data: data
    //         },
    //         success: function (request, xhr, status, error) {
    //             if (request.success === true) {
    //                 $('#woo-customer-form-callback').val('Success');
    //             } else {
    //                 $.each(request.data, function (key, val) {
    //                     form.find(["name="+key]);
    //                     form.find(["name="+key]).before('<span class="error-' + key + '">' + val + '</span>');
    //                 });
    //                 $('#woo-customer-form-callback').val('Error');
    //             }
    //         },
    //         error: function (request, status, error) {
    //             $('#woo-customer-form-callback').val('Error');
    //         }
    //     });
    // });
    var options = {
        url: woo_customer_params.url,
        data: {
            action: 'woo_customer_form_callback',
            nonce: woo_customer_params.nonce
        },
        type: 'POST',
        dataType: 'json',
        beforeSubmit: function (xhr) {
            console.log('beforeSubmit');

        },
        success: function (request, xhr, status, error) {
            if (request.success === true) {
                $('#woo-customer-form-callback').text('Success');
            } else {
                $.each(request.data, function (key, val) {
                    form.find(["name="+key]);
                    form.find(["name="+key]).before('<span class="error-' + key + '">' + val + '</span>');
                });
                $('#woo-customer-form-callback').text('Error');
            }
            console.log('success');
            console.log(status);
        },
        error: function (request, status, error) {
            $('#woo-customer-form-callback').text('Error');
            console.log(error);
            console.log(status);
        }
    };
    form.ajaxForm(options);
});