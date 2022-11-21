/*$function AjaxContentLoader(elemt, dont_close_diags) {
 dont_close_diags = dont_close_diags === undefined ? false : dont_close_diags;
 var href = elemt.attr('href');
 var vclass = elemt.hasClass('vehicle');
 var fclass = elemt.hasClass('fleet');
 var fleet_id = $("#idfleet option:selected").val();
 var unit_id = $("#idvhl option:selected").val();
 var station_id = $("#idstation option:selected").val();
 
 var idviewelement = elemt.attr('help-id')
 //On ferme toutes les boite de dialogues et popup
 if (!dont_close_diags) {
 $('.ui-dialog').dialog('close');
 $('.ui-popup').popup('close');
 }
 //ON quitte
 //unit_id == -100 => Aucune unité dans la flotte
 // Voir dans dynamicSelect
 if ((vclass && (unit_id == -100 || unit_id === '')) || (fclass && fleet_id === '')) {
 $("#page-header a#pageCloser").click();
 return false;
 }
 
 jqmLoading('hide', null)
 
 if (activeMenuLink != null && href == activeMenuLink.attr('href'))
 pageReloading = true;
 else
 pageReloading = false;
 
 activeMenuLink = elemt;
 
 
 jqmLoading('show', elemt); //Affichage du loader jqm
 //href = $(this).attr('href')
 //On annule toute eventuelle requête en cours
 if (atRequest !== null)
 atRequest.abort();
 
 atRequest = $.ajax({
 type: "GET",
 url: href,
 cache: false,
 data: {
 idvhl: unit_id,
 idfleet: fleet_id,
 idstation: station_id,
 menu_script_name: href
 }
 })
 atRequest.done(function(data) {
 $("#content").html(data).trigger("create")
 jqmLoading('hide', null)//Cacher le loader jqm
 //Si c'est le Panel Overlay on ferme
 if ($("#menu-panel").panel("option", "display") == 'overlay')
 $("#menuCloseLink").click()
 
 loadHelpPanelContent(idviewelement);
 
 })
 atRequest.fail(function(jqXHR, textStatus) {
 //alert('Chargement de la page interrompu !');
 atRequest = null
 });
 
 //cf vhlParamsAlarm.php
 clearInterval(setIntervalTimer);
 }
 */

function DeleteData(url, form, message, button_one, button_two, msg2) {
    params = $("#form").serialize();

    $.ajax(
        {
            url: 'url',
            method: 'POST',
            data: params,
            success: function(response) {

              var objet = $.parseJSON(response);
              if (objet.feedback === 0) {
                  $(".message").html(objet.response);
                  $('.button_one').css('display', 'none');
                  $('#msg2').css('display', 'none');
                  $('.button_two').css('display', 'block');

              } else {
                 $(".message").html(objet.feedback);
               }
            },
            dataType: 'text'
        }
    );
}

function setupDataTables(id, options) {
    var responsiveHelper = undefined;
    var breakpointDefinition = {
        tablet: 1024,
        phone: 480
    };
    //
    var tblelt = $("#" + id);
    //Initialisation
    var dtable = tblelt.dataTable(jQuery.extend({
        //"bJQueryUI": true,
        "bStateSave": false,
        "sPaginationType": "full_numbers",
        "oLanguage": {
            "sUrl": site_url + "library/js/datatables/v1.9.1/translations/fr.txt"
        },
        "aLengthMenu": [20, 40, 60, 80, 100],
        "iDisplayLength": 20,
        "sDom": 'T<"clear"><"H"lfr>t<"F"ip>',
        "oTableTools": {
            "aButtons": [
                "copy", "print",
                {
                    "sExtends": "collection",
                    "sButtonText": "Sauvegarder",
                    "aButtons": [
                        {
                            "sExtends": "csv",
                            "sFileName": "tab_html.csv"
                        },
                        {
                            "sExtends": "xls",
                            "sFileName": "tab_html.xls"
                        },
                        {
                            "sExtends": "pdf",
                            "sFileName": "tab_html.pdf"
                        }
                    ]

                }
            ]
        },
        "cSearchable": false,
        bAutoWidth: false,
        fnPreDrawCallback: function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper) {
                responsiveHelper = new ResponsiveDatatablesHelper(tblelt, breakpointDefinition);
            }
        },
        fnRowCallback: function(nRow) {
            responsiveHelper.createExpandIcon(nRow);
        },
        fnDrawCallback: function(oSettings) {
            responsiveHelper.respond();
        }
    }, options));

    /**
     * Ajout de Trie sur une colonne particulière
     * options searchOnColumn
     */
    if (options !== undefined && typeof options.searchOnColumn === 'object') {
        //Controle de l'option
        if (typeof options.searchOnColumn.colIndex === 'number' &&
                typeof options.searchOnColumn.inputLabel === 'string')
        {
            var col_ind = options.searchOnColumn.colIndex;
            var input_lib = options.searchOnColumn.inputLabel;
            var search_start_with = options.searchOnColumn.valueStartWith !== undefined ? options.searchOnColumn.valueStartWith : true;
            var id_search = id + 'Search' + col_ind;
            //Une fois la table initialisée
            tblelt.on('init.dt', function() {
                //Ajout du champs input text de recherche
                $('<div id="Box' + id_search + '" data-enhance="false">\
                    <div class="dataTables_length">&nbsp;</div>\
                    <div class="dataTables_filter" id="vhl_code_filter">\
                        <label>' + input_lib + ' :<input class="Input' + id_search + '" aria-controls="fleetinfoglance-op" type="text"></label>\
                    </div>\
                    </div>').insertBefore(tblelt);
                //Gestion de l'événement de saisi dans le champs input text de recherche
                $("#Box" + id_search + " .Input" + id_search).on("keyup click", function() {
                    if (search_start_with === true)
                        dtable.api().column(col_ind).search('^' + $(this).val(), true).draw();
                    else
                        dtable.api().column(col_ind).search($(this).val()).draw();
                });
            });
        }//if
    }//if
    
    return dtable;
}
