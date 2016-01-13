$(function()
{
    var productsBlock = new ProductSelectionBlock('productsSelection');
    $('#createOrderForm').submit(function(){
        $('#productIds').val(productsBlock.getValue());
    });
});