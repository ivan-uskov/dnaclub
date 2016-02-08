$(function()
{
    jQuery.extend( jQuery.fn.dataTableExt.oSort, {
        "date-ru-pre": function ( a ) {
            if (a == null || a == "") {
                return 0;
            }
            var ukDatea = a.split('.');
            return (ukDatea[2] && ukDatea[1]) ? (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1 : 0;
        },

        "date-ru-asc": function ( a, b ) {
            return ((a < b) ? -1 : ((a > b) ? 1 : 0));
        },

        "date-ru-desc": function ( a, b ) {
            return ((a < b) ? 1 : ((a > b) ? -1 : 0));
        }
    } );
});