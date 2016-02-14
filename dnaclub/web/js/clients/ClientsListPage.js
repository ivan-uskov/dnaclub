$(function()
{
    var PAGE_LENGTH = 25;

    initDataTable();
    attachConfirmToDeleteLinks();
    attachClickToFilterCheckboxes();

    function initDataTable()
    {
        $("#clients_list").DataTable({
            pageLength: PAGE_LENGTH,
            columnDefs: [
                {orderable: false, targets: -1},
                {searchable: false, targets: [4, 5]},
                {type: 'date-ru', targets: [4]}
            ]
        });
    }

    function attachConfirmToDeleteLinks()
    {
        $(".delete-link").click(function(event)
        {
            event.preventDefault();
            var answer = confirm("Вы уверены, что хотите удалить данные о клиенте?");
            if (answer === true)
            {
                location.href = $(this).attr("href");
            }
        });
    }

    function attachClickToFilterCheckboxes()
    {
        var clientsFilterCheckboxes = $(".clients-filter-checkbox");
        clientsFilterCheckboxes.click(function(event)
        {
            clientsFilterCheckboxes.attr("disabled", "disabled");
        });
    }
});