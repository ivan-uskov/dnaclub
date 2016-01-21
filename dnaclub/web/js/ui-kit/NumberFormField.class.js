var NumberFormField = function(id)
{
    var field = $('#' + id);
    var updateHandler = function(){};

    this.setUpdateHandler = function(handler)
    {
        updateHandler = handler;
    };

    this.getValue = function()
    {
        return + field.val();
    };

    this.setValue = function(val)
    {
        var newVal = + val;
        field.val(isNaN(newVal) ? 0 : newVal);
    };

    function initializeHandlers()
    {
        field.change(function(){
            if (field.val() == '')
            {
                field.val(0);
            }
            updateHandler(field.val());
        });

        field.keypress(function(e){
            var key = e.which ? e.which : e.keyCode;
            var char = String.fromCharCode(key);
            return (char >= '0' && char <= '9');
        });
    }

    (function() {
        initializeHandlers();
    })();
};