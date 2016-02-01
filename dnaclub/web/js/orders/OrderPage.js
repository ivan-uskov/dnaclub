$(function()
{
    function update()
    {
        var result = -productsBlock.getCoast() + discount.getValue() + paymentBlock.getSum();
        $('#debt').text(result >= 0 ? 0 : result);
    }

    var discount = new NumberFormField('discount', update);
    var productsBlock = new ProductSelectionBlock('productsSelection', update);
    var paymentBlock = new PaymentSelectionBlock('paidByCash', update);
    update();

    $('#submit').click(function(){
        $('#productsInfo').val(productsBlock.getValue());
        $('#paymentInfo').val(paymentBlock.getValue());
        $('#cost').val(productsBlock.getCoast());
        $('#debtField').val($('#debt').text());

        $('#createOrderForm').submit();
    });
});