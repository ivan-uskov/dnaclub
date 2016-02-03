$(document).ready(function()
{
    initDataTable();
    updateReportResult();
    submitChanges();

    var needSaveData = false;

    function initDataTable()
    {
        $('#marketing_report').DataTable({
            pageLength: 25,
            order: [[3, "desc"]],
            columnDefs: [
                {searchable: false, targets: [1, 2, 3, 4, 5, 6, 7]},
                {orderable: false, targets: [-5, -4, -3]},
                {type: 'formatted-num', targets: [1, 2, 3, 6]}
            ]
        });
    }

    function updateReportResult()
    {
        $("input[name='add_new_subscription']").change(function ()
        {
            var id = $(this).attr("id").match(/\d+/);

            if (id)
            {
                var result_sum = $('#' + id + '_result_sum');
                var initialResultSum = getValue(id, 'initial_result', true);
                var contract = getValue(id, 'add_contract', false);
                var maintenance = getValue(id, 'add_maintenance', false);
                var result = initialResultSum - contract * $('#contract_price').val() - maintenance * $('#maintenance_price').val();
                result_sum.text(accounting.formatMoney(result));

                needSaveData = true;
                $('#submit_subscriptions').show();
                $('#alert-success').hide();
                $('#alert-danger').hide();
            }

            function getValue(id, name, isText)
            {
                var val = 0;
                var obj = $('#' + id + '_' + name);

                if (isText)
                {
                    val = parseFloat(obj.text());
                }
                else
                {
                    val = obj.val();
                }

                return (val) ? val : 0;
            }
        });
    }

    function submitChanges()
    {
        $('#submit_subscriptions').on('click', function ()
        {
            var contracts = {};
            var maintenance = {};
            $("input[name='add_new_subscription']").each(function ()
            {
                var idObj = $(this).attr("id");
                var id = idObj.match(/\d+/);
                var val = $(this).val();

                if (val != 0)
                {
                    if (idObj.search('contract') != -1)
                    {
                        contracts[id] = val;
                    }
                    else
                    {
                        maintenance[id] = val;
                    }
                }
            });

            $('#contract_result').val(JSON.stringify(contracts));
            $('#maintenance_result').val(JSON.stringify(maintenance));
            needSaveData = false;
            $('#save_subscription_form').submit();
        });
    }

    window.onbeforeunload = function ()
    {
        return (needSaveData ? "Измененные данные не сохранены." : null);
    }

});