$(function()
{
    var subscriptionForm = $("#add_form");
    var buttonAddTop = $("#button_add_top");

    var PAGE_LENGTH = 25;

    var commonColumnDefs = [
        {orderable: false, targets: [5]},
        {searchable: false, targets: [1, 2, 4, 5]},
        {type: 'date-ru', targets: [1]},
        {type: 'formatted-num', targets: [4]}
    ];

    var clientColumnDefs = [{visible: false, targets: 0}];

    if ($("#template_mode").val() == "subscriptions")
    {
        $("#subscription_list").DataTable({
            pageLength: PAGE_LENGTH,
            order: [[0, "asc"]],
            columnDefs: commonColumnDefs
        });
    }
    else
    {
        $("#subscription_list").DataTable({
            pageLength: PAGE_LENGTH,
            order: [[1, "desc"]],
            bFilter: false,
            bLengthChange: false,
            columnDefs: commonColumnDefs.concat(clientColumnDefs)
        });
    }

    buttonAddTop.click(function(event)
    {
        event.preventDefault();
        subscriptionForm.removeClass("hidden");
        buttonAddTop.addClass("hidden");
    });
});