$(function()
{
    var PAGE_LENGTH = 10;

    $('#ordersList').on('click', '.remove_order_button', function()
    {
        return confirm('Вы точно хотите удалить продажу?');
    }).DataTable({
        pageLength: PAGE_LENGTH,
        order: [[ 0, "desc" ]],
        columnDefs: [
            {orderable: false, targets: 7},
            {searchable: false, targets: [1, 2, 3, 4]}
        ]
    });
});
