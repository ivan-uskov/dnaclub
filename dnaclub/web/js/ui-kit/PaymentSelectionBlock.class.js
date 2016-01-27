var PaymentSelectionBlock = function(id)
{
    var list = $('#' + id + 'List');
    var stubCashItem = $('#' + id + 'StubCashRow');
    var stubRewordItem = $('#' + id + 'StubRewordRow');
    var updateHandler  = function(){};

    this.setUpdateHandler = function(handler)
    {
        updateHandler = handler;
    };

    $('#' + id + 'AddByCash').click(function(){
        insertClone(stubCashItem);
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

    this.getValue = function()
    {
        var ids = [];
        return ids.join(',');
    };

    function getCoast()
    {
        var coast = 0;

        return + coast;
    }

    function initializeHandlers()
    {
        list.on('click', '.remove_payment', function()
        {
            $(this).parents('tr').remove();
            updateHandler();
        });
    }

    function initializeAutoComplete()
    {

    }


    this.getCoast = getCoast;

    (function()
    {
        initializeHandlers();
        initializeAutoComplete();
    })();
};