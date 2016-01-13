var ProductSelectionBlock = function(id)
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
            ids.push($(this).attr('data-product-id'));
        });

        return ids.join(',');
    };

    function appendItem(id, name, price)
    {
        var newItem = stubItem.clone();
        newItem.attr('data-product-id', id);
        newItem.find('.price').first().text(price);
        newItem.html(name + newItem.html());
        newItem.removeClass('hidden');
        list.append(newItem);
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
        });
    }

    function initializeAutoComplete()
    {
        var productNames = $.map(products, function(value, key) {return key;});
        inserter.typeahead({source: productNames});
    }

    function initialize()
    {
        initializeHandlers();
        initializeAutoComplete();
    }

    initialize();
};