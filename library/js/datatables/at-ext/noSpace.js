/**
 * Les valeurs de cette colonne ne condiendra pas d'espace pour le tri et les recherches
 * 
 *  @summary Permet de rechercher la chaine et de la trier convenablement en ignorant les espace blancs
 *  @name +228 90 39 96 29
 *  @author Eric AKAKPO
 *
 *  @example
 *    $(document).ready(function() {
 *      $('#example').dataTable( {
 *        columnDefs: [
 *          { type: 'noSpace', target: 4 }
 *        ]
 *      });
 *    });
 */
$.fn.dataTable.ext.type.search.noSpace = function (data) {
    //
    return !data ? '' : (typeof data === 'string' ? data.replace(/\s/g, '') : data);
};

$.fn.dataTable.ext.type.order['noSpace-pre'] = function (data) {
    //
    return $.fn.dataTable.ext.type.search.noSpace(data);
};