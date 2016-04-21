var PaymentSelectionBlock = function(id, updateHandler)
{
    var cashItemInputs = [];
    var rewordItemInputs = [];
    var list = $('#' + id + 'List');
    var stubCashItem = $('#' + id + 'StubCashRow');
    var stubRewordItem = $('#' + id + 'StubRewordRow');
    var clientRewardsAjaxUrlPattern = $('#rewardsListUrl').val();
    var rewards = [];
    var addByRewardButtonDisabled;

    $('#' + id + 'AddByCash').click(function(){
        var newItem = insertClone(stubCashItem);
        var uniqId = 'id' + (new Date()).getTime();

        var input = newItem.find('input.sum').attr('id', uniqId);
        var field = new NumberFormField(uniqId, updateHandler);
        cashItemInputs.push(field);
    });

    $('#' + id + 'AddByReword').click(function(){
        if (addByRewardButtonDisabled)
        {
            alert('Подождите пару секунд, данные обновляются');
            return false;
        }
        var newItem = insertClone(stubRewordItem);
        var select = newItem.find('select').first();
        if (select.length)
        {
            $.each(rewards, function(i, rewardInfo) {
                select.append('<option value="' + rewardInfo.id +'" data-remainig="' + rewardInfo.remaining + '" data-sum="' + rewardInfo.sum + '">' + rewardInfo.name +  '</option>');
            });
        }
        var uniqId = 'id' + (new Date()).getTime();
        var input = newItem.find('input.sumHolder').attr('id', uniqId);
        var field = new NumberFormField(uniqId, updateHandler);
        rewordItemInputs.push(field);
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

    function removeRow(row)
    {
        var id = row.find('.sum').attr('id');
        deleteNumberField(id);
        row.remove();
    }

    function removeNewRewardPayments()
    {
        list.find('.reword.new').each(function()
        {
            removeRow($(this));
        });

        updateHandler();
    }

    this.updateRewardsList = function(clientId)
    {
        removeNewRewardPayments();
        var url = clientRewardsAjaxUrlPattern.replace('CLIENT_ID', clientId);
        addByRewardButtonDisabled = true;
        $.post(url).done(function(response) {
            addByRewardButtonDisabled = false;
            rewards = response.rewards;
            var button = $('#paidByCashAddByReword');
            if (rewards.length)
            {
                button.css('display', 'inline-block');
            }
            else
            {
                button.hide();
            }
        }).fail(function() {
            alert('Произошла ошибка, перезагрузите страницу!');
        });
    };

    this.getSum = function()
    {
        var value = 0;

        list.find('tr').each(function() {
            var row = $(this);
            if (!row.hasClass('hidden'))
            {
                var input = row.find('input.sum');
                if (input.length)
                {
                    value += + input.val();
                }
            }
        });

        return value;
    };

    this.getValue = function()
    {
        var rewardValue = [];
        var cashValue = [];

        list.find('tr.cash.new').each(function() {
            var row = $(this);
            if (!row.hasClass('hidden'))
            {
                cashValue.push({
                    date: row.find('.date').first().val(),
                    sum: row.find('.sum_holder').first().val()
                });
            }
        });

        list.find('tr.reword.new').each(function() {
            var row = $(this);
            if (!row.hasClass('hidden'))
            {
                rewardValue.push({
                    reward_id: row.find('select').first().val(),
                    sum: row.find('.sumHolder').first().val(),
                    date: row.find('.date').first().val()
                });
            }
        });

        return $.toJSON({cash: cashValue, reward: rewardValue});
    };

    function getCost()
    {
        var cost = 0;

        return + cost;
    }

    function deleteNumberField(id)
    {
        $.each(cashItemInputs, function(i, field) {
            if (field.getId() == id)
            {
                delete cashItemInputs[i];
            }
        });
        $.each(rewordItemInputs, function(i, field) {
            if (field.getId() == id)
            {
                delete cashItemInputs[i];
            }
        });
    }

    function initializeHandlers()
    {
        list.on('click', '.remove_payment', function()
        {
            removeRow($(this).parents('tr'));
            updateHandler();
        });
    }

    this.getCost = getCost;

    (function()
    {
        initializeHandlers();
    })();
};