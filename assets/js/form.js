jQuery(document).ready(function($) {
    var form = $("#woo-customer-form");
    var options = {
        url: woo_customer_params.url,
        data: {
            action: 'woo_customer_form_callback',
            nonce: woo_customer_params.nonce
        },
        type: 'POST',
        dataType: 'json',
        beforeSubmit: function (xhr) {

        },
        success: function (request, xhr, status, error) {
            if (request.success === true) {
                $('#woo-customer-form-callback').addClass('success').removeClass('error').text('Success');
                $("#woo-customer-form")[0].reset();
            } else {
                $('#woo-customer-form-callback').addClass('error').removeClass('success').text(request.data.name);
            }
            console.log(error);
            console.log(status);
        },
        error: function (request, status, error) {
            $('#woo-customer-form-callback').text('Error');
            console.log(error);
            console.log(status);
        }
    };
    form.ajaxForm(options);

    $('#woo-customer-pagination *').click(function(e) {
        e.preventDefault();
        let page = $(this).text();
        let url = woo_customer_params_table.url;
        let data = {
            url: woo_customer_params_table.url,
            type: 'POST',
            nonce: woo_customer_params_table.nonce,
            action: 'woo_customer_table_callback',
            page: page,
        };
        $.ajax({
            url: woo_customer_params_table.url,
            type: "POST",
            dataType: 'json',
            data: {
                nonce: woo_customer_params_table.nonce,
                action: 'woo_customer_table_callback',
                page: page,
            },
            success: function (data) {
                console.log(data);
                let customers = '';
                $.each(data, function(_key, data) {
                    customers += '<tr><td>' + data['display_name'] + '</td><td>' + data['user_email']  + '</td></tr>';
                });
                $('.woo-customer-table').find('tbody').html(customers);
                $('#woo-customer-pagination *').removeClass('current');
                $('#woo-customer-pagination *:contains(' + page + ')').addClass('current');
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
    });
});