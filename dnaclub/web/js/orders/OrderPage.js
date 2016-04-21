$(function()
{
    function update()
    {
        var cost = productsBlock.getCoast();
        $('#coast').text(cost);

        var debt = cost - discount.getValue() - paymentBlock.getSum();
        $('#debt').text(debt <= 0 ? 0 : debt);
    }

    var clientSelect = $('#userName');
    var discount = new NumberFormField('discount', update);
    var productsBlock = new ProductSelectionBlock('productsSelection', update);
    var paymentBlock = new PaymentSelectionBlock('paidByCash', update);
    paymentBlock.updateRewardsList(clientSelect.find('option:selected').val());
    clientSelect.change(function() {
        paymentBlock.updateRewardsList(clientSelect.val());
    });
    update();

    $('#submit').click(function(){
        var productInfoValue = productsBlock.getValue();
        if (productInfoValue == '')
        {
            alert('Добавьте продукты!');
            return false;
        }

        $('#productsInfo').val(productInfoValue);
        $('#paymentInfo').val(paymentBlock.getValue());
        $('#cost').val(productsBlock.getCoast());
        $('#debtField').val($('#debt').text());

        $('#createOrderForm').submit();
    });

    $('#isPreOrder').change(function(){
        if (this.checked)
        {
            $('#plannedDate').show();
            $('#actualDate').show();
        }
        else
        {
            $('#plannedDate').hide();
            $('#actualDate').hide();
        }
    });
});