jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "date-custom-pre": function ( a ) {
        if ($.trim(a) != '') {
            var frDatea = $.trim(a).split(' ');
            var frTimea = frDatea[1].split(':');
            var frDatea2 = frDatea[0].split('-');
            console.log(frDatea, frTimea, frDatea2);
            return (frDatea2[2] + frDatea2[1] + frDatea2[0] + frTimea[0] + frTimea[1] + frTimea[2]) * 1;
        }

        return 10000000000000; // = l'an 1000 ...
    },

    "date-custom-asc": function ( a, b ) {
        return a - b;
    },

    "date-custom-desc": function ( a, b ) {
        return b - a;
    }
} );