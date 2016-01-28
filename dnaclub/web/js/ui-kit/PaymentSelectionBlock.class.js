var PaymentSelectionBlock = function(id, updateHandler)
{
    var cashItemInputs = [];
    var list = $('#' + id + 'List');
    var stubCashItem = $('#' + id + 'StubCashRow');
    var stubRewordItem = $('#' + id + 'StubRewordRow');

    $('#' + id + 'AddByCash').click(function(){
        var newItem = insertClone(stubCashItem);
        var uniqId = 'id' + (new Date()).getTime();

        var input = newItem.find('input.sum').attr('id', uniqId);
        var field = new NumberFormField(uniqId, updateHandler);
        cashItemInputs.push(field);
    });

    $('#' + id + 'AddByReword').click(function(){
        insertClone(stubRewordItem);
    });

    function insertClone(item)
    {
        var newItem = item.clone();
        newItem.removeClass('hidden');
        list.append(newItem);
        initDatePicker(newItem.find('.date_picker'));
        updateHandler();

        return newItem;
    }

    function initDatePicker(item)
    {
        item.datepicker({
            format: 'dd.mm.yyyy',
            weekStart: 1,
            autoclose: true,
            language: 'ru'
        });
    }

    this.getSum = function()
    {
        var value = 0;

        $.each(cashItemInputs, function(i, field){
            value += field.getValue();
        });

        return value;
    };

    this.getValue = function()
    {
        var cashValue = [];

        list.find('tr.cash').each(function() {
            var row = $(this);
            if (!row.hasClass('hidden'))
            {
                cashValue.push({
                    id: row.attr('data-payment-id'),
                    date: row.find('.date').first().val(),
                    sum: row.find('.sum_holder').first().val()
                });
            }
        });

        return $.toJSON({cash: cashValue});
    };

    function getCoast()
    {
        var coast = 0;

        return + coast;
    }

    function deleteNumberField(id)
    {
        $.each(cashItemInputs, function(i, field) {
            if (field.getId() == id)
            {

            }
        });
    }

    function initializeHandlers()
    {
        list.on('click', '.remove_payment', function()
        {
            var row = $(this).parents('tr');
            var id = row.find('.sum').attr('id');
            deleteNumberField(id);
            row.remove();
            updateHandler();
        });
    }

    function initializeCashItemInputs()
    {
        $('#' + id + 'List').find('tr.cash').each(function() {
            var row = $(this);
            if (!row.hasClass('hidden'))
            {
                var uniqId = 'id' + (new Date()).getTime();
                var input = row.find('input.sum').attr('id', uniqId);
                var field = new NumberFormField(uniqId, updateHandler);
                cashItemInputs.push(field);
                initDatePicker(row.find('.date_picker'));
            }
        });
    }

    this.getCoast = getCoast;

    (function()
    {
        initializeHandlers();
        initializeCashItemInputs();
    })();
};