$(function()
{
    var PAGE_LENGTH = 10;

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
            columnDefs: [
                { orderable: false, targets: [1, 6] },
                { searchable: false, targets: [2, 4, 5] },
                { type: 'date-ru', targets: [2] },
                { type: 'formatted-num', targets: [4, 5] }
            ]
        });
    }
    else
    {
        ordersList.DataTable({
            pageLength: PAGE_LENGTH,
            bFilter: false,
            bLengthChange: false,
            order: [[ 1, "desc" ]],
            columnDefs: [
                { orderable: false, targets: [0, 5] },
                { searchable: false, targets: [1, 3, 4] },
                { type: 'date-ru', targets: [1] },
                { type: 'formatted-num', targets: [3, 4] }
            ]
        });
    }
});
