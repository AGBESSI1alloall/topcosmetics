
/**
 * Effectue la requête de soumission du formulaire
 *
 * @param {type} sfx
 * @returns {undefined}             
 * 
 */
function AjaxLoader(url, dataObject, container, divReload, scriptReload, reloadParams, reload_fleet_list) {

    reloadParams = typeof reloadParams !== 'undefined' ? reloadParams : "";
    reload_fleet_list = typeof reload_fleet_list !== 'undefined' ? reload_fleet_list : false;
    var tabReload = true
    var tabToReload = divReload //
    var scriptToReload = scriptReload

    //Blocage du button valider-----
    submitButton = $(".ui-dialog").find(".twobutton").find("input[type='button']")
    if (submitButton.length > 0) {
        buttonText = submitButton.val()//.button('disable')
        submitButton.val(saving_lib).button('refresh')//.button('disable')
    }


    container.html("");
    $.mobile.loading("show");

    $.ajax({
        type: "POST",
        url: url,
        data: dataObject
    }).done(function(msg, textStatus, jqXHR, temoinajaxLoader) {
        var objet = $.parseJSON(msg); //jsonToObject(msg) JSON string TO object
        $.mobile.loading("hide");
        console.log(objet);
        if (objet.feedback === 0) {

            container.html(objet.response).trigger('create')

            $(".ui-dialog, .ui-popup").find(".twobutton").hide()
            $(".ui-dialog, .ui-popup").find(".onebutton").show()


            //Pour les actions a effectuer sans attendre "pageshow"
            if (typeof (tabToReload) == 'function') {
                tabToReload();
                tabReload = false;
            }

            $(document).on('pageshow', '#seul-page', function() {
                setTimeOutTimer = setTimeout(function() {
                    if (tabReload == true) {
                        if (tabToReload == 'wholeContent') {
                            AjaxContentLoader(activeMenuLink)

                        } else  //Si on doit revalider un filtre par clique sur son button
                        if (tabToReload == 'click' && scriptToReload.length) {
                            scriptToReload.click();
                        } else if (tabToReload != null && scriptToReload != null && tabToReload.length != 0) {
                            $.mobile.loading("show");

                            var t = new Date();
                            var t = t.getTime();
                            $(tabToReload).load(scriptToReload,
                                    function() {
                                        $(this).trigger('create');
                                        $.mobile.loading("hide");
                                    })
                        }
                    }//+ "?_=" + t + "&idvhl=" + $("#idvhl option:selected").val() + "&idfleet=" + $("#idfleet option:selected").val() + '&' + reloadParams
                    tabReload = false;
                    tabToReload = "";
                    scriptToReload = "";
                    //ON recharge la liste des flottes si indiquée : 
                    //utilisé pour fleetsSettingsList.php -> fleetAddForm2.php
                    if (reload_fleet_list) {
                        reloadFleetList();
                    }
                    console.log('#seul-page : pageshow');
                }, 100)
            });
            setTimeOutTimer = setTimeout(function() {
                //$('.ui-popup').popup('close');
                //$('.ui-dialog').dialog('close');
            }, 2500);

        } else {
            container.html(objet.feedback).trigger('create');
        }

        //Réactivation du button valider
        if (submitButton.length > 0) {
            submitButton.val(buttonText).button('enable').button('refresh')
        }

    }).fail(function(jqXHR, textStatus) {
        alert('Chargement interrompu !\n' + textStatus /* + ' -- ' + jqXHR*/);
    });
}

/**
 * 
 *
 * @param {type} link
 * @returns {undefined}            
 */
function deletePopupSet(paramObject, deleteId, sfx) {
    //sfx n'est plus utilisé
    sfx = typeof sfx !== 'undefined' ? sfx : "";
    //var div_deletePopup = "div#deletePopup" + sfx;
    var div_deletePopup = $.mobile.activePage.find('.deletePopup');
    //Prépare le Popup de suppression
    div_deletePopup.find(".ui-btn").removeClass('ui-btn-active')

    if (paramObject.title != null)
        div_deletePopup.find("h1.deleteTitle").html(paramObject.title)

    if (paramObject.display != null)
        div_deletePopup.find("form.deleteConfirmForm .deleteConfirmMessBox").html(paramObject.display)

    if (paramObject.width != null)
        div_deletePopup.css('width', paramObject.width + 'px')

    div_deletePopup.find("form.deleteConfirmForm input[name='deleteId']").val(deleteId)

    div_deletePopup.find("form.deleteConfirmForm input[name='deleteScript']").val(paramObject.deleteScript)
    div_deletePopup.find("form.deleteConfirmForm input[name='deleteType']").val(paramObject.deleteType)
    div_deletePopup.find("form.deleteConfirmForm input[name='scriptToReload']").val(paramObject.scriptToReload)
    div_deletePopup.find("form.deleteConfirmForm input[name='divToReload']").val(paramObject.divToReload)
    //Weither should reload header filter fleet list
    paramObject.reloadFleetsList = paramObject.reloadFleetsList != undefined ? paramObject.reloadFleetsList : 'no';
    div_deletePopup.find("form.deleteConfirmForm input[name='reloadFleetsList']").val(paramObject.reloadFleetsList)
}

/**
 * 
 *
 * @param {type} link
 * @returns {undefined}             
 */
function deletePopupOpen(deleteObject, deleteId, sfx) {
    sfx = typeof sfx !== 'undefined' ? sfx : "";
    var div_deletePopup = $.mobile.activePage.find('.deletePopup'); //"div#deletePopup" + sfx;
    deletePopupReset(sfx);
    //Prépare le Popup de suppression
    deletePopupSet(deleteObject, deleteId, sfx);
    div_deletePopup.popup("open", {transition: $.mobile.defaultPopupTransition, positionTo: 'window'});
}

/**
 * 
 *
 * @param {type} link
 * @returns {undefined}            
 */
function deletePopupReset(sfx) {
    //Reset the Popup to default values
    sfx = typeof sfx !== 'undefined' ? sfx : "";
    var div_deletePopup = $.mobile.activePage.find('.deletePopup');
    //Paramètre de préchargement du dialog de suppression
    deletePopupSet(defaultPopupParams, '', sfx);
    //
    div_deletePopup.find("form.deleteConfirmForm .twobutton").show();
    div_deletePopup.find("form.deleteConfirmForm .onebutton").hide();
    div_deletePopup.find("form.deleteConfirmForm .onebuttonerror").hide();
}



/*
 * Affichage position sur map au clique petite icon map à coté
 * des libellé de Lieux
 * @param {JQuery object} link Le lien cliqué  
 * */
function atPopMap(link) {
    pophref = "";
    if (link.attr('popmaptype') === undefined) {

        data = {
            idfleet: $("#idfleet option:selected").val(), //unused
            idvhl: link.attr('idvhl')
        }
        pophref = "uipopupLocalization.php";
    } else if (link.attr('popmaptype') == 'area') {

        data = {
            idfleet: $("#idfleet option:selected").val(), //unused
            popmaptype: link.attr('popmaptype'),
            idarea: link.attr('idarea'),
            areatype: link.attr('areatype')
        }
        pophref = "winpopupFormerLocalization.php";
    } else if (link.attr('popmaptype') == 'point') {
        data = {
            idfleet: $("#idfleet option:selected").val(), //unused
            idvhl: link.attr('idvhl'),
            popmaptype: link.attr('popmaptype'),
            popmapdate: link.attr('popmapdate'),
            not_dt_datepos: link.attr('not_dt_datepos') !== undefined ? link.attr('not_dt_datepos') : 'false'
        }
        pophref = "winpopupFormerLocalization.php";
    } else if (link.attr('popmaptype') == 'point-area') {
        data = {
            idfleet: $("#idfleet option:selected").val(), //unused
            idvhl: link.attr('idvhl'),
            popmaptype: link.attr('popmaptype'),
            popmapdate: link.attr('popmapdate'),
            idarea: link.attr('idarea'),
            areatype: link.attr('areatype'),
            not_dt_datepos: link.attr('not_dt_datepos') !== undefined ? link.attr('not_dt_datepos') : 'false'
        }
        pophref = "winpopupFormerLocalization.php";
    } else if (link.attr('popmaptype') == 'trip-point') {
        data = {
            idfleet: $("#idfleet option:selected").val(), //unused
            idvhl: link.attr('idvhl'),
            popmaptype: link.attr('popmaptype'),
            popmapdate: link.attr('popmapdate'),
            popmapdate2: link.attr('popmapdate2'),
            not_dt_datepos: link.attr('not_dt_datepos') !== undefined ? link.attr('not_dt_datepos') : 'false'
        }
        pophref = "winpopupFormerLocalization.php";
    } else if (link.attr('popmaptype') == 'trip-point-area') {
        data = {
            idfleet: $("#idfleet option:selected").val(), //unused
            idvhl: link.attr('idvhl'),
            popmaptype: link.attr('popmaptype'),
            popmapdate: link.attr('popmapdate'),
            popmapdate2: link.attr('popmapdate2'),
            idarea: link.attr('idarea'),
            areatype: link.attr('areatype'),
            not_dt_datepos: link.attr('not_dt_datepos') !== undefined ? link.attr('not_dt_datepos') : 'false'
        }
        pophref = "winpopupFormerLocalization.php";
    }


    width = 450;
    height = 350;
    if (window.innerWidth) {
        var left = (window.innerWidth - width) / 2;
        var top = (window.innerHeight - height) / 2;
    } else {
        var left = (document.body.clientWidth - width) / 2;
        var top = (document.body.clientHeight - height) / 2;
    }

    url = pophref + '?' + $.param(data);

    winParam = 'titlebar=no,toolbar=no,location=no,menubar=no, scrollbars=no, top=' + top + ', left=' + left + ', width=' + width + ', height=' + height + ''

    atPopMapWin = window.open(url, null, winParam);
}

/**
 * Vérifie s'il y a un événement du jour du calendrier de maintenance
 * et le signal par un message alert
 *
 * @returns {undefined}             
 */
function checkEventCalendar() {
    var today = new Date();
    var today = (today.getTime()) / 1000;
    var param = {
        today: today,
        weither_event_today: 1
    };

    $.ajax({
        type: "GET",
        url: "events_all_today.php",
        data: param
    }).done(function(msg) {
        if (msg != 0) {
            var opts = {
                positionTo: "window",
                transition: $.mobile.defaultPopupTransition,
                dismissible: false,
                overlayTheme: 'a'
            };
            atPopup(msg, null, opts);
        }
    }).fail(function(jqXHR, textStatus) {
        console.log(textStatus + ' -- ' + jqXHR);
    });
}

/**
 * Vérifie si l'utilisateur a des messages non lus
 * et le signal par un message alert
 *
 * @returns {undefined}             
 */
function checkUserMessages() {
    var today = new Date();
    var today = (today.getTime()) / 1000;
    var param = {
        action: 'check',
        today: today,
    };

    $.ajax({
        type: "GET",
        url: "userMessageLoader.php",
        data: param
    }).done(function(msg) {
        if (msg != 'no-message') {
            var opts = {
                positionTo: "window",
                transition: $.mobile.defaultPopupTransition,
                dismissible: false,
                overlayTheme: 'a'
            };
            atPopup(msg, null, opts);
        }
    }).fail(function(jqXHR, textStatus) {
        console.log(textStatus + ' -- ' + jqXHR);
    });
}

function notificationList() {//Affichage de la liste des notifications
    $("#notificationPopup").html(loading).popup('open', {'positionTo': 'window'});
    $('#notificationPopup').load('notificationListForm.php', function() {
        $('#notificationPopup').trigger('create');
        $("#notificationPopup").popup('reposition', {'positionTo': 'window'});
    });

}
function notificationClose() {//cacher définitivement la notification pour cette session

    $.ajax({
        dataType: "html",
        url: "session.php",
    }).done(function(msg) {
        //alert(0);
        flag = 0;
    })

}

function notificationDelete(idalarm) {//supprimer une notification                
    $.ajax({
        'dataType': 'json',
        type: "GET",
        url: "notification.php",
        data: {'idalarm': idalarm},
    }).done(function(msg) {
        //alert(msg);return false;
        if (msg.idalarm == 0) {
            closeNotification();
            var tst = 0;
        } else {
            showNotification({
                //message: '<b>' + msg.alarm_data + '</b>&nbsp;<a onclick="notificationDelete(' + msg.idalarm + ')" title="Supprimer cette notification" data-wrapperels="span" data-iconshadow="true" data-shadow="true" data-corners="true" href="#" idalarm="' + msg.idalarm + '" class=" delete ui-btn ui-shadow ui-btn-corner-all ui-mini ui-btn-inline ui-btn-icon-notext ui-btn-up-c" data-theme="c" data-rel="dialog" data-role="button" data-icon="delete" data-iconpos="notext" data-mini="true" data-inline="true"><span class="ui-btn-inner"><span class="ui-btn-text">Supprimer</span><span class="ui-icon ui-icon-delete ui-icon-shadow" style="background-color: rgba(190,11,11,.81);">&nbsp;</span></span></a>&nbsp;&nbsp;&nbsp;<a href="#" id="shownotificationlist" style="color:#a39709" onclick=" closeNotification(); notificationList()">Afficher plus...</a>',
                message: msg.alarm_data,
                type: "warning",
                playSound: playNotifSound
            });
        }
    });
}

function success_msg(msg) {
    return "\
    <p class='success'>\
    <a href='#' class='successIcon simple-btn ui-btn ui-btn-up-c ui-shadow ui-btn-corner-all ui-mini ui-btn-inline ui-btn-icon-notext' data-icon='check' data-theme='c' data-role='button' data-inline='true' data-iconpos='notext' data-mini='true' data-corners='true' data-shadow='true' data-iconshadow='true' data-wrapperels='span' title=''><span class='ui-btn-inner'><span class='ui-btn-text'></span><span class='ui-icon ui-icon-check ui-icon-shadow'>&nbsp;</span></span></a>\
    " + msg + "</p> ";
}

function info_msg(msg) {
    return "\
    <p class='infos'>\
    <a href='#' data-icon='info' data-theme='c' data-role='button' data-inline='true' data-iconpos='notext' data-mini='true' data-corners='true' data-shadow='true' data-iconshadow='true' data-wrapperels='span' title='' class='ui-btn ui-btn-up-c ui-shadow ui-btn-corner-all ui-mini ui-btn-inline ui-btn-icon-notext'><span class='ui-btn-inner'><span class='ui-btn-text'></span><span class='ui-icon ui-icon-info ui-icon-shadow'>&nbsp;</span></span></a>\
    " + msg + "</p> ";
}

function error_msg(msg) {
    return "\
    <p class='error'>\
    <a href='#' class='at-delete ui-btn ui-shadow ui-btn-corner-all ui-mini ui-btn-inline ui-btn-icon-notext ui-btn-up-c' data-icon='alert' data-theme='c' data-role='button' data-inline='true' data-iconpos='notext' data-mini='true' data-corners='true' data-shadow='true' data-iconshadow='true' data-wrapperels='span' title=''><span class='ui-btn-inner'><span class='ui-btn-text'></span><span class='ui-icon ui-icon-alert ui-icon-shadow'>&nbsp;</span></span></a>\
    " + msg + "</p>";
}

function AjaxDelete(url, donne, donnString,twoBtn,oneBtn,firstString) {

    $.ajax(
            {
                url: url,
                method: 'POST',
                data: donne,
                success: function(response) {

                    var objet = $.parseJSON(response);
                    if (objet.feedback === 0) {
                        donnString.html(objet.response);
                        twoBtn.css('display', 'none');
                        firstString.css('display', 'none');
                        oneBtn.css('display', 'block');

                    } else {
                        donnString.html(objet.feedback);
                    }
                },
                dataType: 'text'
            }
    );
}

//Data Table

function setupDataTables(id, options) {
    var responsiveHelper = undefined;
    var breakpointDefinition = {
        tablet: 1024,
        phone: 480
    };
    var tblelt = $("#" + id);

    tblelt.dataTable(jQuery.extend({
        //"bJQueryUI": true,
        "bStateSave": false,
        "sPaginationType": "full_numbers",
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
        bAutoWidth: false,
        fnPreDrawCallback: function () {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper) {
                responsiveHelper = new ResponsiveDatatablesHelper(tblelt, breakpointDefinition);
            }
        },
        fnRowCallback: function (nRow) {
            responsiveHelper.createExpandIcon(nRow);
        },
        fnDrawCallback: function (oSettings) {
            responsiveHelper.respond();
            console.log("ORIGNIAL EXECUTED");
        }
    }, options));
    
    }
    
    
function ReconnectLogin(timereconnect){
    if( timereconnect == 1){
      window.location ="../logout.php";  
    }else{
        
    }
}