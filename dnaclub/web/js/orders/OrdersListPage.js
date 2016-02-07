$(function()
{
    var PAGE_LENGTH = 10;

    var commonColumnDefs = [
        { orderable: false, targets: [1, 7] },
        { searchable: false, targets: [2, 4, 5, 6, 7] },
        { type: 'date-ru', targets: [2] },
        { type: 'formatted-num', targets: [4, 7] }
    ];

    var clientColumnDefs = [{visible: false, targets: 0 }];

    var ordersList = $('#ordersList');
    ordersList.on('click', '.remove_order_button', function()
    {
        return confirm('Вы точно хотите удалить продажу?');
    });

    if ($('#template_mode').val() == 'orders')
    {
        ordersList.DataTable({
            pageLength: PAGE_LENGTH,
            bFilter: false,
            bLengthChange: false,
            order: [[ 2, "desc" ]],
            columnDefs: commonColumnDefs
        });
    }
    else
    {
        ordersList.DataTable({
            pageLength: PAGE_LENGTH,
            order: [[ 2, "desc" ]],
            columnDefs: commonColumnDefs.concat(clientColumnDefs)
        });
    }
});
