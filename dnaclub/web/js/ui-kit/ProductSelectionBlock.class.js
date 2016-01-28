var ProductSelectionBlock = function(id, updateHandler)
{
    var list = $('#' + id + 'List');
    var stubItem = list.find('.stub_product');
    var inserter = $('#' + id + 'Inserter');
    var products = JSON.parse(inserter.attr('data-products'));

    this.getValue = function()
    {
        var ids = [];

        list.find('.product').each(function()
        {
            ids.push($(this).attr('data-product-id') + ':' + $(this).find('.count').first().val());
        });

        return ids.join(',');
    };

    function getCoast()
    {
        var coast = 0;

        list.find('.product').each(function()
        {
            var item = $(this);
            coast += (parseInt(item.find('.price').first().text())) * parseInt(item.find('.count').first().val());
        });

        return + coast;
    }

    function getItem(id)
    {
        return list.find('.product[data-product-id=' + id + ']').first();
    }

    function appendItem(id, name, price)
    {
        var item = getItem(id);
        if (item.length)
        {
            var count = item.find('.count').first();
            count.val(+count.val() + 1);
        }
        else
        {
            var newItem = stubItem.clone();
            newItem.attr('data-product-id', id);
            newItem.find('.price').first().text(price);
            newItem.html(name + newItem.html());
            newItem.removeClass('hidden');
            newItem.removeClass('stub_product');
            newItem.addClass('product');
            list.append(newItem);
        }

        $('#coast').text(getCoast());
        updateHandler();
    }

    function tryAddProduct()
    {
        if (products[inserter.val()] !== undefined)
        {
            var product = products[inserter.val()];
            appendItem(product.id, product.name, product.price);
            inserter.val('');
        }
    }

    function initializeHandlers()
    {
        inserter.keypress(function(e){
            var key = e.which ? e.which : e.keyCode;
            if (key == 13) // enter
            {
                tryAddProduct();
                return false;
            }
        });

        list.on('click', '.remove_product', function(){
            $(this).parent().remove();
            updateHandler(getCoast());
        });

        list.on('change', '.count', function(){
            var obj = $(this);
            if (obj.val() == '')
            {
                obj.val(1);
            }
            updateHandler(getCoast());
        });

        list.on('keypress', '.count', function(e){
            var key = e.which ? e.which : e.keyCode;
            var char = String.fromCharCode(key);
            return (char >= '0' && char <= '9');
        });
    }

    function initializeAutoComplete()
    {
        var productNames = $.map(products, function(value, key) {return key;});
        inserter.typeahead({source: productNames});
    }


    this.getCoast = getCoast;

    (function()
    {
        initializeHandlers();
        initializeAutoComplete();
    })();
};