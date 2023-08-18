(function($) {

    const table = new DataTable('#hyperlinks-data-table', {
        columnDefs: [
            {
                searchable: false,
                orderable: false,
                targets: 0
            },
            { width: "5%", targets: 0 }
        ],
        order: [[1, 'asc']]
    });

    table.on('order.dt search.dt', function () {
        let i = 1;
        table
            .cells(null, 0, { search: 'applied', order: 'applied' })
            .every(function () {
                this.data(i++);
            });
    }).draw();

    function updateDataTable(data) {
        const dataTable = new DataTable('#hyperlinks-data-table'); // Get the DataTable instance
        dataTable.clear().rows.add(data.map(function (link) {
            return ['', '<a href="' + link + '" target="_blank">' + link + '</a>'];
        })).draw();
    }

    $(document).on('click', '#wsc-generate', function () {
        $.ajax({
            url: AdminMyAjax.ajaxurl,
            data: {
                action: 'wsc_report_generate',
                security: AdminMyAjax.wsc_admin_security_nonce
            },
            type: "post",
            beforeSend: function () {
                $('.wsc-wrap .loader').show();
            },
            success: function (response) {
                if (response.error) {
                    $('.msg-wrap').html('<div class="error"><p>' + response.msg + '</p></div>');
                    $('.msg-wrap').fadeIn();
                    setTimeout(function () {
                        $(".msg-wrap").fadeOut();
                    }, 3000);
                }

                if (response.success) {
                    $('.msg-wrap').html('<div class="updated"><p>' + response.msg + '</p></div>');
                    $('.msg-wrap').fadeIn();
                    setTimeout(function () {
                        $(".msg-wrap").fadeOut();
                    }, 3000);
                }
                if (response.data) {
                    updateDataTable(response.data);
                }
            },
            complete: function () {
                $('.wsc-wrap .loader').hide();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            },
        });
    });

})(jQuery);


