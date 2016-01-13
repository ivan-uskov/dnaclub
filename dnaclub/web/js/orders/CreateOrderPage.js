$(function()
{
    function onProductsUpdate(newCoast)
    {
        $('#coast').text(newCoast);
    }

    var productsBlock = new ProductSelectionBlock('productsSelection');
    productsBlock.setUpdateHandler(onProductsUpdate);

    $('#createOrderForm').submit(function(){
        $('#productIds').val(productsBlock.getValue());
    });
});