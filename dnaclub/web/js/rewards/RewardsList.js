$(function()
{
    var PAGE_LENGTH = 25;

    initDatePicker();
    attachConfirmToDeleteLinks();
    initDataTable();

    function initDatePicker()
    {
        $('.datepicker').datepicker({
            format: 'dd.mm.yyyy',
            weekStart: 1,
            autoclose: true,
            language: 'ru'
        });
    }

    function attachConfirmToDeleteLinks()
    {
        $(".delete-link").click(function(event)
        {
            event.preventDefault();
            var answer = confirm("Вы уверены?");
            if (answer === true)
            {
                location.href = $(this).attr("href");
            }
        });
    }

    function initDataTable()
    {
        var commonColumnDefs = [
            {orderable: false, targets: [-1, -2, -3]},
            {searchable: false, targets: [-1, -2, -3, -4, -5, -6]},
            {type: 'date-ru', targets: [1]},
            {type: 'formatted-num', targets: [2, 3]}
        ];

        var rewardColumnDefs = [{visible: false, targets: 1 }];
        var clientColumnDefs = [{visible: false, targets: 0 }];

        if ($("#template_mode").val() == "rewards")
        {


            $("#rewards_list").DataTable({
                pageLength: PAGE_LENGTH,
                order: [[3, "desc"]],
                columnDefs: commonColumnDefs.concat(rewardColumnDefs)
            });
        }
        else
        {
            $("#rewards_list").DataTable({
                pageLength: PAGE_LENGTH,
                order: [[3, "desc"]],
                columnDefs: commonColumnDefs.concat(clientColumnDefs)
            });
        }
    }

    var subscriptionForm = $("#add_form");
    var buttonAddTop = $("#button_add_top");
    buttonAddTop.click(function(event)
    {
        event.preventDefault();
        subscriptionForm.removeClass("hidden");
        buttonAddTop.addClass("hidden");
    });
});