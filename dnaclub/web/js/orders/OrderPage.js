$(function()
{
    function update()
    {
        var result = -productsBlock.getCoast() + discount.getValue() + paidByCash.getValue();
        debt.setValue(result >= 0 ? 0 : result);
    }

    new PaymentSelectionBlock('paidByCash');

    var discount = new NumberFormField('discount');
    var paidByCash = new NumberFormField('paidByCash');
    var debt = new NumberFormField('debt');

    discount.setUpdateHandler(update);
    paidByCash.setUpdateHandler(update);

    function onProductsUpdate(newCoast)
    {
        $('#coast').text(newCoast);
        update();
    }

    var productsBlock = new ProductSelectionBlock('productsSelection');
    productsBlock.setUpdateHandler(onProductsUpdate);

    $('#createOrderForm').submit(function(){
        $('#productsInfo').val(productsBlock.getValue());
        $('#cost').val(productsBlock.getCoast());
    });
});