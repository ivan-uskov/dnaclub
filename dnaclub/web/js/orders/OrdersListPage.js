$(function()
{
    var PAGE_LENGTH = 25;

    $('#ordersList').on('click', '.remove_order_button', function()
    {
        return confirm('Вы точно хотите удалить продажу?');
    }).DataTable({
        pageLength: PAGE_LENGTH,
        order: [[ 2, "desc" ]],
        columnDefs: [
            { orderable: false, targets: [1, 6] },
            { searchable: false, targets: [2, 4, 5] },
            { type: 'date-ru', targets: [2] },
            { type: 'formatted-num', targets: [4, 5] }
        ]
    });
});
