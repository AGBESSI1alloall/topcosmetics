//
// Add DateTime Sort to Datatables Jquery Plugin
// Format 23-10-2011 12:34 
//

jQuery.fn.dataTableExt.aTypes.push(
        function(sData) {
            if (sData == null){
                return null;
            }
            if (sData.charAt(2) == '-' && sData.charAt(5) == '-' && sData.length == 16) { // Choose format 23-10-2011 18:30  ou 23/10/2011 13:20
                return 'date_fr';
            }
        }
);

function trim(str) {
    str = str.replace(/^\s+/, '');
    for (var i = str.length - 1; i >= 0; i--) {
        if (/\S/.test(str.charAt(i))) {
            str = str.substring(0, i + 1);
            break;
        }
    }
    return str;
}
function dateHeight(dateStr) {
    if (trim(dateStr) != '' && dateStr.length > 9) {
        var frDate = trim(dateStr).split(' ');
        var frTime = frDate[1].split(':');
        var frDateParts = frDate[0].split('-'); // Choose format 23-10-2011 ou 23/10/2011
        var day = frDateParts[0] * 60 * 24;
        var month = frDateParts[1] * 60 * 24 * 31;
        var year = frDateParts[2] * 60 * 24 * 366;
        var hour = frTime[0] * 60;
        var minutes = frTime[1];
        var x = day + month + year + hour + minutes;
    } else {
        var x = 99999999999999999; //GoHorse!
    }
    return x;
}

jQuery.fn.dataTableExt.oSort['date_fr-asc'] = function(a, b) {
    var x = dateHeight(a);
    var y = dateHeight(b);

    return ((x < y) ? -1 : ((x > y) ? 1 : 0));
};

jQuery.fn.dataTableExt.oSort['date_fr-desc'] = function(a, b) {
    var x = dateHeight(a);
    var y = dateHeight(b);

    return ((x < y) ? 1 : ((x > y) ? -1 : 0));
};
