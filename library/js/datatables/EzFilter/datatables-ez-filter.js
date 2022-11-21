/* 
 * --- PARAMETRAGES DEFINIS
 * **Possibilité d'index de columns négatifs
 * **options par defaut : {type='input'}
 * **Pas de EzFilter => data-ezfilter='none' ou data-ezfilter='type=none'
 * ezFilter et ezLanguage (string ou object)
 * ezIndexing : fals par defaut
 * Paramètre de langue : internationalization
 * Supprimer une ancienne ligne de EzFilter qui existait déjà (déjà fait) : Incorporer EzFilter une seule fois sur la page
 * 
 * --- AUTRES PARAMETRES A REVOIR
 * dtEzFilterSearchTimeOut : Temps de latence pour les recherche par input ?
 * Permettre des expressions régulières
 * Server side management * 
 * 
 * CSS & framework gérés par datatables : bootstrap 3 et 4, Jquery UI, Fondation ...
 * 
 * Test avec les différents plugins dataTables comme scroll, fixedHeader
 * 
 * _/ AutoFill (Les valeurs de modifications existent déjà dans les select des filtres)
 * _/ Button : Fully compatible
 * ColReorder (Problème d'index de colonne pour la recherche)
 * Editor (Ajout/Modif des valeurs de cellule)
 * FixedColumns : Crée une autre table qu'il superpose à la table initiale (incompatible avec EzFilter)
 *              Solution : Déplacer les filter dans le <th> de l'entête
 * Scroller : Cache la ligne de EzFilter (Déplacer EzFilter dans les <th> des cellules ? )
 * FixedHeader _/ : Ok, mais je dois cacher multi-select popup on mouse-scroll event
 * _/ KeyTable 
 * Responsive : Cache des <th>, Ne cache pas les <td> de tr.EzFilter. 
 * _/ RowGroup : 
 * _/ RowReorder: 
 * _/ SearchBuilder
 * _/ SearchPanes
 * _/ Select
 * DateTime (Standalone)
 * 
 * 
 * 
 * Auto indexation de ligne
 * zIndex de multi-select-box (max zindex datatables + 1)
 * 
 * -------
 * Tester dans plusieurs navigateurs
 * 
 * ---------
 * EzFilter doit être chargé une seule fois sur la page
 */

(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD
        define(['jquery', 'datatables.net'], function ($) {
            return factory($, window, document);
        });
    }
    else if (typeof exports === 'object') {
        // CommonJS
        module.exports = function (root, $) {
            if (!root) {
                root = window;
            }

            if (!$ || !$.fn.dataTable) {
                $ = require('datatables.net')(root, $).$;
            }

            return factory($, root, root.document);
        };
    }
    else {
        // Browser
        factory(jQuery, window, document);
    }
}(function ($, window, document, undefined) {
    //
    'use strict';
    //
    var _ezLanguages = {
        'en': {
            'sOk': "OK",
            'sCancel': "Cancel",
            'sSearch': "Search",
            'sSelectAll': "Select all",
            'sNoValue': "No value"
        },
        'fr': {
            'sOk': "OK",
            'sCancel': "Annuler",
            'sSearch': "Rechercher",
            'sSelectAll': "Sélectionner tout",
            'sNoValue': "Aucune valeur"
        }
    };

    var _ezHtmls = {
        'default': {
            'input': "<input type='text' class='ezinput' />",
            'select': "<select class='ezselect'><option value='' ></option></select>",
            'multi_choice': "<select class='multi-choice'></select>\
                            <div class='ez-multi-choice-box' id='@mchoice_id@'>\
                                <div class='choice-popup'>\
                                    <div class='btn-box'>\
                                        <button class='ez-btn validate' value='ok'>@sOk@</button>&nbsp;&nbsp;\
                                        <button class='ez-btn cancel' value='cancel'>@sCancel@</button>\
                                    </div>\
                                    <div class='search-box'>\
                                        <input type='text' class='search' value='' placeholder='@sSearch@...' />\
                                    </div>\
                                    <div class='wrapper'>\
                                        <div class='choice-box'>\
                                            <div class='choice-list ez-checkbox'></div>\
                                        </div>\
                                        <div class='choice-all ez-checkbox'>\
                                            <label class='check-all check-label' for='@check_all_id@'>@sSelectAll@\
                                                <input type='checkbox' id='@check_all_id@' class='check-all' checked>\
                                                <span class='checkmark'></span>\
                                            </label>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>",
            'multi_choice_checkbox': "<label for='@check_id@' class='check-label ez-check-line'>@check_label@\
                                    <input type='checkbox' id='@check_id@' value='@check_value@' checked>\
                                    <span class='checkmark'></span>\
                                    </label>"
        },
        'jquery-ui': {
            'input': "<input type='text' class='ezinput' />",
            'select': "<select class='ezselect'><option value='' ></option></select>",
            'multi_choice': "<select class='multi-choice'></select>\
                            <div class='ez-multi-choice-box' id='@mchoice_id@'>\
                                <div class='choice-popup'>\
                                    <div class='btn-box'>\
                                        <button class='ez-btn validate ui-button ui-widget ui-corner-all' value='ok'>@sOk@</button>&nbsp;&nbsp;\
                                        <button class='ez-btn cancel ui-button ui-widget ui-corner-all' value='cancel'>@sCancel@</button>\
                                    </div>\
                                    <div class='search-box'>\
                                        <input type='text' class='search' value='' placeholder='@sSearch@...' />\
                                    </div>\
                                    <div class='wrapper'>\
                                        <div class='choice-box'>\
                                            <div class='choice-list ez-checkbox'></div>\
                                        </div>\
                                        <div class='choice-all ez-checkbox'>\
                                            <label class='check-all check-label' for='@check_all_id@'>@sSelectAll@\
                                                <input type='checkbox' id='@check_all_id@' class='check-all' checked>\
                                                <span class='checkmark'></span>\
                                            </label>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>",
            'multi_choice_checkbox': "<label for='@check_id@' class='check-label ez-check-line'>@check_label@\
                                    <input type='checkbox' id='@check_id@' value='@check_value@' checked>\
                                    <span class='checkmark'></span>\
                                    </label>"
        },
        'jqm132': {
            'input': "<input type='text' class='ezinput' data-role='none' data-enhance='false' />",
            'select': "<select class='ezselect' data-role='none' data-enhance='false'><option value='' ></option></select>",
            'multi_choice': "<select class='multi-choice' data-role='none' data-enhance='false'></select>\
                            <div class='ez-multi-choice-box' id='@mchoice_id@' data-role='none' data-enhance='false'>\
                                <div class='choice-popup'>\
                                    <div class='btn-box'>\
                                        <button class='ez-btn validate' value='ok' data-role='none'>@sOk@</button>&nbsp;&nbsp;\
                                        <button class='ez-btn cancel' value='cancel' data-role='none'>@sCancel@</button>\
                                    </div>\
                                    <div class='search-box'>\
                                        <input type='text' class='search' value='' data-role='none' placeholder='@sSearch@...' />\
                                    </div>\
                                    <div class='wrapper'>\
                                        <div class='choice-box'>\
                                            <div class='choice-list ez-checkbox'></div>\
                                        </div>\
                                        <div class='choice-all ez-checkbox'>\
                                            <label data-role='none'  class='check-all check-label' for='@check_all_id@'>@sSelectAll@\
                                                <input type='checkbox' id='@check_all_id@' class='check-all' data-role='none' checked>\
                                                <span class='checkmark'></span>\
                                            </label>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>",
            'multi_choice_checkbox': "  <label for='@check_id@' data-role='none' class='check-label ez-check-line'>@check_label@\
                                    <input type='checkbox' id='@check_id@' value='@check_value@' data-role='none' checked>\
                                    <span class='checkmark'></span>\
                                    </label>"
        },
        'bootstrap3': {
            'input': "<input type='text' class='ezinput form-control input-sm' />",
            'select': "<select class='ezselect form-control input-sm'><option value='' ></option></select>",
            'multi_choice': "<select class='multi-choice form-control input-sm'></select>\
                            <div class='ez-multi-choice-box' id='@mchoice_id@'>\
                                <div class='choice-popup form-inline'>\
                                    <div class='btn-box'>\
                                        <button class='ez-btn validate btn btn-primary btn-sm' value='ok'>@sOk@</button>&nbsp;&nbsp;\
                                        <button class='ez-btn cancel btn btn-default btn-sm' value='cancel'>@sCancel@</button>\
                                    </div>\
                                    <div class='search-box'>\
                                        <input type='text' class='search form-control input-sm' value='' placeholder='@sSearch@...' />\
                                    </div>\
                                    <div class='wrapper'>\
                                        <div class='choice-box'>\
                                            <div class='choice-list ez-checkbox'></div>\
                                        </div>\
                                        <div class='choice-all ez-checkbox'>\
                                            <label class='check-all check-label' for='@check_all_id@'>@sSelectAll@\
                                                <input type='checkbox' id='@check_all_id@' class='check-all' checked>\
                                                <span class='checkmark'></span>\
                                            </label>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>",
            'multi_choice_checkbox': "<label for='@check_id@' class='check-label ez-check-line'>@check_label@\
                                    <input type='checkbox' id='@check_id@' value='@check_value@' checked>\
                                    <span class='checkmark'></span>\
                                    </label>"
        },
        'bootstrap4': {
            'input': "<input type='text' class='ezinput form-control form-control-sm' />",
            'select': "<select class='ezselect form-control form-control-sm'><option value='' ></option></select>",
            'multi_choice': "<select class='multi-choice form-control form-control-sm'></select>\
                            <div class='ez-multi-choice-box' id='@mchoice_id@'>\
                                <div class='choice-popup form-inline'>\
                                    <div class='btn-box'>\
                                        <button class='ez-btn validate btn btn-primary btn-sm' value='ok'>@sOk@</button>&nbsp;&nbsp;\
                                        <button class='ez-btn cancel btn btn-secondary btn-sm' value='cancel'>@sCancel@</button>\
                                    </div>\
                                    <div class='search-box'>\
                                        <input type='text' class='search form-control form-control-sm' value='' placeholder='@sSearch@...' />\
                                    </div>\
                                    <div class='wrapper'>\
                                        <div class='choice-box'>\
                                            <div class='choice-list ez-checkbox'></div>\
                                        </div>\
                                        <div class='choice-all ez-checkbox'>\
                                            <label class='check-all check-label' for='@check_all_id@'>@sSelectAll@\
                                                <input type='checkbox' id='@check_all_id@' class='check-all' checked>\
                                                <span class='checkmark'></span>\
                                            </label>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>",
            'multi_choice_checkbox': "<label for='@check_id@' class='check-label ez-check-line'>@check_label@\
                                    <input type='checkbox' id='@check_id@' value='@check_value@' checked>\
                                    <span class='checkmark'></span>\
                                    </label>"
        },
        'bootstrap5': {
            'input': "<input type='text' class='ezinput form-control form-control-sm' />",
            'select': "<select class='ezselect form-select form-select-sm'><option value='' ></option></select>",
            'multi_choice': "<select class='multi-choice form-select form-select-sm'></select>\
                            <div class='ez-multi-choice-box' id='@mchoice_id@'>\
                                <div class='choice-popup'>\
                                    <div class='btn-box'>\
                                        <button class='ez-btn validate btn btn-primary btn-sm' value='ok'>@sOk@</button>&nbsp;&nbsp;\
                                        <button class='ez-btn cancel btn btn-secondary btn-sm' value='cancel'>@sCancel@</button>\
                                    </div>\
                                    <div class='search-box'>\
                                        <input type='text' class='search form-control form-control-sm' value='' placeholder='@sSearch@...' />\
                                    </div>\
                                    <div class='wrapper'>\
                                        <div class='choice-box'>\
                                            <div class='choice-list ez-checkbox'></div>\
                                        </div>\
                                        <div class='choice-all ez-checkbox'>\
                                            <div class='form-check'>\
                                                <input class='check-all form-check-input' type='checkbox' id='@check_all_id@' checked>\
                                                <label class='check-all form-check-label check-label' for='@check_all_id@'>@sSelectAll@</label>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>",
            'multi_choice_checkbox': "<div class='form-check ez-check-line'>\
                                        <input class='form-check-input' type='checkbox' value='@check_value@' id='@check_id@' checked>\
                                        <label class='form-check-label check-label' for='@check_id@'>@check_label@</label>\
                                      </div>"
        },
        'foundation6': {
            'input': "<input type='text' class='ezinput' />",
            'select': "<select class='ezselect small'><option value='' ></option></select>",
            'multi_choice': "<select class='multi-choice small'></select>\
                            <div class='ez-multi-choice-box' id='@mchoice_id@'>\
                                <div class='choice-popup'>\
                                    <div class='btn-box'>\
                                        <button class='ez-btn validate button primary small' value='ok'>@sOk@</button>&nbsp;&nbsp;\
                                        <button class='ez-btn cancel button secondary small' value='cancel'>@sCancel@</button>\
                                    </div>\
                                    <div class='search-box'>\
                                        <input type='text' class='search' value='' placeholder='@sSearch@...' />\
                                    </div>\
                                    <div class='wrapper'>\
                                        <div class='choice-box'>\
                                            <div class='choice-list ez-checkbox'></div>\
                                        </div>\
                                        <div class='choice-all ez-checkbox'>\
                                            <label class='check-all check-label' for='@check_all_id@'>\
                                                <input class='check-all' type='checkbox' id='@check_all_id@' checked>@sSelectAll@\
                                            </label>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>",
            'multi_choice_checkbox': "<label for='@check_id@' class='check-label ez-check-line'>\
                                        <input type='checkbox' id='@check_id@' value='@check_value@' checked>@check_label@\
                                        </label>"
        },
        'semantic-ui-2': {
            'input': "<div class='ui form'><div class='ui mini input'><input type='text' class='ezinput' /></div></div>",
            'select': "<div class='ui form mini'><div class='field'><select class='ezselect'><option value='' ></option></select></div></div>",
            'multi_choice': "<div class='ui form mini'><div class='field'><select class='multi-choice'></select></div></div>\
                            <div class='ez-multi-choice-box ui form' id='@mchoice_id@'>\
                                <div class='choice-popup'>\
                                    <div class='btn-box'>\
                                        <button class='ez-btn validate mini ui button blue' value='ok'>@sOk@</button>&nbsp;&nbsp;\
                                        <button class='ez-btn cancel mini ui button black' value='cancel'>@sCancel@</button>\
                                    </div>\
                                    <div class='search-box ui mini input'>\
                                        <input type='text' class='search' value='' placeholder='@sSearch@...' />\
                                    </div>\
                                    <div class='wrapper'>\
                                        <div class='choice-box'>\
                                            <div class='choice-list ez-checkbox'></div>\
                                        </div>\
                                        <div class='ui field'>\
                                        <div class='choice-all ez-checkbox ui checkbox'>\
                                            <input class='check-all' type='checkbox' id='@check_all_id@' class='hidden' checked>\
                                            <label class='check-all check-label' for='@check_all_id@'>@sSelectAll@</label>\
                                        </div></div>\
                                    </div>\
                                </div>\
                            </div>",
            'multi_choice_checkbox': "<div class='ui field ez-check-line'><div class='ui checkbox'>\
                                        <input type='checkbox' id='@check_id@' class='hidden' value='@check_value@' checked>\
                                        <label for='@check_id@' class='check-label'>@check_label@</label>\
                                    </div></div>"
        }
    };



    /**
     * On exécute EzFilter à l'initialisation de chaque table
     */
    $(document).on('init.dt.dtr', function (e, settings, json) {
        //console.log("-------------------");
        /**
         * 
         */
        var table = $(e.target); //The Jquery objet of the table
        var dtable = new $.fn.dataTable.Api(e.target); //Datatable Api instance of the table
        var init_options = dtable.init();
        var nb_cols = dtable.columns().count();
        console.log(init_options);

        /***********************************************************************
         * Ez-Indexing
         */
        var ezIndexingColumns = []; //Liste des colonnes servant d'index (1 seul normalement)
        //Paramètres d'Indexation par data-attribute ------------
        dtable.columns().every(function () {
            var attr = $(this.header()).attr('data-ezindex');
            if (attr !== undefined && attr !== false && attr !== null)
                ezIndexingColumns[this.index()] = true;//.push(this.index());
            else
                ezIndexingColumns[this.index()] = false;
        });
        //Paramètres d'Indexation ezIndexing  ------------
        if (typeof init_options.ezIndexing !== 'undefined') {
            if (init_options.ezIndexing === true)
                ezIndexingColumns[0] = true;
            else
            if (Array.isArray(init_options.ezIndexing)) {
                init_options.ezIndexing.forEach(function (col_i) {
                    ezIndexingColumns[col_i >= 0 ? col_i : nb_cols + col_i] = true;
                })
            }
        }
        //Application de l'auto indexation des colonnes concernées (Seule la 1er colonne en générale)
        dtable.columns().every(function () {
            var col_ind = this.index();
            if (ezIndexingColumns[col_ind] === true) {
                //On active la numérotation automatique pour cette colonne
                dtable.on('order.dt search.dt', function () {
                    dtable.column(col_ind, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).on('draw.dt', function () { //Pour JQuery Mobile
                    $(this).trigger('create')
                }).draw();
            }
        });
        console.log(ezIndexingColumns)




        /***********************************************************************
         * Ez-Filter
         */
        //On quitte si aucun paramétrage ezFilter
        if (!(
                typeof init_options.ezFilter === 'object' || //Paramétrage depuis paramètres datatables
                table.attr('data-ezfilter') !== undefined || //Paramétrage pour toutes les colonnes de la table
                table.find('thead tr th[data-ezfilter]').length > 0 //Paramétrage par colonne
                ))
            return;
        //
        //console.log('Paramétrage ezFilter okay ** !');
        //
        var dtEzFilterSearchTimeOut = []; //

        /**
         * Paramètres de langue
         */
        var lang = _ezLanguages['en']; //Langue par defaut
        //Utilsation de Code de langue par l'utilisateur : 'fr', 'en'
        if (typeof init_options.ezLanguage === 'string' && typeof _ezLanguages[init_options.ezLanguage] === 'object')
            lang = _ezLanguages[init_options.ezLanguage];
        else
        //Utilisation d'un fichier JSON
        if (typeof init_options.ezLanguage === 'object' && (typeof init_options.ezLanguage.url === 'string' || typeof init_options.ezLanguage.sUrl === 'string')) {
            var json_url;
            if (typeof init_options.ezLanguage.url === 'string')
                json_url = init_options.ezLanguage.url;
            else
                json_url = init_options.ezLanguage.sUrl;
            //
            $.ajax({
                async: false,
                dataType: "json",
                url: json_url,
                success: function (data) {
                    $.extend(lang, data);
                }
            });
        } else
        //Utilisation d'un objet langue
        if (typeof init_options.ezLanguage === 'object')
            $.extend(lang, init_options.ezLanguage);

        /**
         * Contenu HTML tags (balises)
         */
        var ezHtml = _ezHtmls['jqm132'];
        ezHtml = typeof init_options.ezHtml === 'string' ? _ezHtmls[init_options.ezHtml] : ezHtml;
        ezHtml = typeof init_options.ezHtml === 'object' ? init_options.ezHtml : ezHtml;


        /**************************
         * Récupération et Préparation des options de paramétrage ezFilter
         */
        //Liste des paramètres pour chaque colonne pour la création du filtre
        var filterOpts = [];
        //----Initialisation des paramètres à null
        dtable.columns().every(function () {
            filterOpts[this.index()] = null;
            dtEzFilterSearchTimeOut[this.index()] = false;
        });

        //-----Traitement des Paramètres de filtre définis par l'attribut "data-ezfilter" de <table>
        var attr = table.attr('data-ezfilter');
        if (attr !== undefined && attr !== false && attr !== null) {
            var f_opt = {type: 'input', wordBeginsWith: true, options: null}; //Valeur par defaut
            //data-ezfilter est sous la forme : type=input|wordBeginsWith=false|options=options_object
            var opts = attr.split('|');
            //
            for (p in opts) {
                var opt = opts[p].split('=');
                if (opt[0] === 'type')
                    f_opt.type = opt.length > 1 ? opt[1] : f_opt.type;
                if (opt[0] === 'wordBeginsWith')
                    f_opt.wordBeginsWith = opt.length > 1 ? eval(opt[1]) : f_opt.type;
                if (opt[0] === 'options') {
                    if (opt.length > 1) { //Sous forme JSON ou nom de variable javascript
                        f_opt.options = opt[1].search('{') > -1 ? $.parseJSON(opt[1]) : eval(opt[1]);
                    }
                }
            }
            //Condition Normalement inutile car le param dans <table> a la plus faible priorité
            //par rapport au paramétrage dans <th> et dans init_options (javascript)
            if (attr === 'none' || f_opt.type === 'none')
                f_opt = null;

            //On applique le paramétrage à chaque colonne
            dtable.columns().every(function () {
                filterOpts[this.index()] = Object.assign({}, f_opt);
            });
        }

        //-----Traitement des Paramètres de filtre définis par l'attribut "data-ezfilter" des <th> de l'entête
        dtable.columns().every(function () {
            attr = $(this.header()).attr('data-ezfilter');
            //
            if (attr !== undefined && attr !== false && attr !== null) {
                var f_opt = {type: 'input', wordBeginsWith: true, options: null}; //Valeur par defaut
                //data-ezfilter est sous la forme : type=input|wordBeginsWith=false|options=options_object
                var attr_array = attr.split('|');
                for (p in attr_array) {
                    var opt = attr_array[p].split('=');
                    if (opt[0] === 'type')
                        f_opt.type = opt.length > 1 ? opt[1] : f_opt.type;
                    if (opt[0] === 'wordBeginsWith')
                        f_opt.wordBeginsWith = opt.length > 1 ? eval(opt[1]) : f_opt.type;
                    if (opt[0] === 'options') {
                        if (opt.length > 1) { //Sou forme JSON ou nom de variable
                            f_opt.options = opt[1].search('{') > -1 ? $.parseJSON(opt[1]) : eval(opt[1]);
                        }
                    }
                    //Si on doit annuler le paramétrage précédement effectué pour cette colonne
                    if (attr === 'none' || f_opt.type === 'none')
                        f_opt = null;
                }
                //Affectation des paramètres à la colonne
                filterOpts[this.index()] = f_opt;
            }
        });

        //-----Traitement des Paramètres de ezFilter fournis dans les "options d'initailisation" de dataTable()    
        //Pour chaque objet de paramètres
        //
        for (var p in init_options.ezFilter) {
            var opt = init_options.ezFilter[p];
            var f_opt = {}; //Option de filtre
            f_opt.type = undefined === opt.type ? 'input' : opt.type; //Type input|select|multi-choice
            f_opt.wordBeginsWith = undefined === opt.wordBeginsWith ? true : opt.wordBeginsWith;
            f_opt.options = undefined === opt.options ? null : opt.options; //Tableau des <option> de <select>
            //On affect les paramètres à chaque colonne concernée
            if (Array.isArray(opt.columns)) {
                opt.columns.forEach(function (val) {
                    var col_id = null;
                    var col_opt = Object.assign({}, f_opt);
                    //S'il y a un object list de <option> pour chaque colonne
                    if (Array.isArray(val) && (f_opt.type === 'select' || f_opt.type === 'multi-choice')) {
                        col_opt.options = val[1]; //val[1] est la liste de valeurs pour les <option>
                        col_id = parseInt(val[0]);//val[0] est l'index
                    } else //Sinon on affecte les paramètre à  la colonne simplement
                        col_id = val;
                    //Si l'index est négative (compte de droite à gauche) on calcule sa valeur réelle
                    col_id = col_id < 0 ? nb_cols + col_id : col_id;

                    //Si on doit annuler le paramétrage précédement effectué pour cette colonne
                    if (col_opt.type === 'none')
                        filterOpts[col_id] = null;
                    else //Sinon on affecte le paramétrage
                        filterOpts[col_id] = col_opt;
                    //
                });
            }
        }

        console.log('***** EzFilter executed');
        //console.log(filterOpts);

        /**************************
         * Création de filtre pour chaque colonnne
         */
        var tr = $("<tr class='ez-filter'>");
        var there_is_filter = false; //Indique si un filtre a été créé pour au moins 1 colonne

        //Création de filtre pour chaque colonne du tableau
        dtable.columns().every(function () {
            var column = this;
            var ind = column.index();
            var col_opt = filterOpts[ind]; //Paramètres pour la création de filtre
            //
            var th = $("<td></td>");
            tr.append(th);
            //        
            //S'il y a des paramètres pour cette colonne on crée le filtre
            if (col_opt !== null && typeof col_opt === "object") {
                var elt = null;
                //Filtre de type <input>    --------------------------------
                if (col_opt.type === 'input') {
                    elt = th.html(ezHtml.input).find('input.ezinput'); //L'input
                    elt.on('keyup change clear', function () { //Evénement déclenchant le filtre
                        if (column.search() !== elt.val()) {
                            //Annulation du précédent compte à rebours de recherche
                            if (dtEzFilterSearchTimeOut[ind])
                                clearTimeout(dtEzFilterSearchTimeOut[ind]);
                            //
                            dtEzFilterSearchTimeOut[ind] = setTimeout(function () { //Compte à rebours pour lancer la recherche
                                //Si le contenu de la cellule doit commencer par le mot recherché
                                if (col_opt.wordBeginsWith === true) {
                                    column.search('^' + $.fn.dataTable.util.escapeRegex(elt.val()), true).draw();
                                } else //Si le contenu de la cellule doit juste contenir le mot recherché
                                    column.search(elt.val()).draw();
                                //Un peu de css
                                elt.val() === "" ? elt.removeClass('filter-on') : elt.addClass('filter-on');
                            }, 250);
                        }
                    });
                    //Affectation du filtre à la cellule de <thead>
                    //if (elt !== null)
                    // th.append(elt);
                } else
                //Filtre de type <select>   --------------------------------
                if (col_opt.type === 'select') {
                    elt = th.html(ezHtml.select).find('select.ezselect');
                    //Contenu du select ----
                    //Si la liste des <option> est passée en paramètres
                    if (col_opt.options !== null && typeof col_opt.options === 'object') {
                        for (var p in col_opt.options) {
                            elt.append($('<option value="' + p + '">' + col_opt.options[p] + '</option>'));
                        }
                    } else { //Sinon on crée la liste à partir des données de la colonne 
                        var ez_options = column.ez_options();
                        for (var d in ez_options) {
                            if (d !== "")
                                elt.append($('<option value="' + d + '">' + d + '</option>'));
                        }
                    }//
                    //Recherche via le <select>
                    elt.on('change', function () {
                        if (column.search() !== elt.val()) {
                            var search_string = elt.val() === "" ? elt.val() : '^' + $.fn.dataTable.util.escapeRegex(elt.val()) + '$';
                            column.search(search_string, true).draw();
                            //Un peut de css
                            elt.val() === "" ? elt.removeClass('filter-on') : elt.addClass('filter-on');
                        }
                    });
                    //Affectation du filtre à la cellule de <thead>
                    //if (elt !== null)
                    //th.append(elt);
                } else
                //Filtre de type <select multiple>  ------------------------
                if (col_opt.type === 'multi-choice') {
                    var mchoices_id = e.target.id + "-mchoices-" + ind;
                    var checkall_id = "check-all-" + ind;
                    //
                    var fm = ezHtml.multi_choice;
                    var fm = fm.replaceAll('@mchoice_id@', mchoices_id);
                    var fm = fm.replaceAll('@sOk@', lang.sOk);
                    var fm = fm.replaceAll('@sCancel@', lang.sCancel);
                    var fm = fm.replaceAll('@sSearch@', lang.sSearch);
                    var fm = fm.replaceAll('@sSelectAll@', lang.sSelectAll);
                    var fm = fm.replaceAll('@check_all_id@', checkall_id);
                    //
                    elt = th.html(fm).find(".ez-multi-choice-box .choice-list");
                    //Selectionner Tout est en gras
                    th.find('.ez-multi-choice-box .choice-all .check-label').addClass('ft-bold');
                    //

                    var inp_id = "";
                    //Contenu du select ----
                    //Si la liste des <option> est passée en paramètres
                    if (col_opt.options !== null && typeof col_opt.options === 'object') {
                        var x = 1;
                        for (var p in col_opt.options) {
                            inp_id = 'search' + ind + '-' + x++;
                            var check_box = ezHtml.multi_choice_checkbox;
                            var check_box = check_box.replaceAll('@check_id@', inp_id);
                            var check_box = check_box.replaceAll('@check_label@', col_opt.options[p]);
                            var check_box = check_box.replaceAll('@check_value@', p);
                            elt.append($(check_box));
                        }
                    } else { //Sinon on crée la liste à partir des données de la colonne 
                        var values_checks = "";
                        var there_is_novalue = false;
                        //Pour les différentes valeurs de la colonne
                        var x = 1;
                        var ez_options = column.ez_options();
                        for (var d in ez_options) {
                            if (d !== "") {
                                inp_id = 'search' + ind + '-' + x++;
                                var check_box = ezHtml.multi_choice_checkbox;
                                var check_box = check_box.replaceAll('@check_id@', inp_id);
                                var check_box = check_box.replaceAll('@check_label@', d);
                                var check_box = check_box.replaceAll('@check_value@', d);
                                values_checks += check_box;
                            } else
                                there_is_novalue = true;
                        }
                        //S'il existe une cellule à valeur vide
                        if (there_is_novalue) {
                            var check_box = ezHtml.multi_choice_checkbox;
                            var check_box = check_box.replaceAll('@check_id@', 'search' + ind + '-0');
                            var check_box = check_box.replaceAll('@check_label@', '( ' + lang.sNoValue + ' )');
                            var check_box = check_box.replaceAll('@check_value@', "");
                            values_checks = check_box + '<div style="height:6px;"></div>' + values_checks;
                        }
                        //On insére la liste de valeurs
                        elt.html(values_checks);
                        elt.find('label[for=search' + ind + '-0]').addClass('ft-bold ft-italic');
                    }//
                    //Gestion des événements    ---------
                    $(document).on('mouseup wheel', function (e) {
                        if (!elt.closest('td').find('select.multi-choice').is(e.target) &&
                                !elt.closest('.ez-multi-choice-box').is(e.target) &&
                                elt.closest('.ez-multi-choice-box').has(e.target).length === 0) {
                            if (elt.closest('.ez-multi-choice-box').hasClass('poped'))
                                elt.closest('.ez-multi-choice-box').find('.ez-btn.cancel').trigger('click');
                        }
                    });
                    //Récemment ajouté à cause de FixedHeader
                    /*elt.closest('.ez-multi-choice-box').on('wheel', function(e){
                        console.log("wheel");
                        e.stopPropagation();
                    })*/
                    elt.closest('td').find('select.multi-choice').on('click keypress focus', function (e) {
                        if (elt.closest('.ez-multi-choice-box').hasClass('poped')) {
                            elt.closest('.ez-multi-choice-box').find('.ez-btn.cancel').trigger('click');
                        } else {
                            elt.find('input:checked').addClass('pre-checked');
                            elt.closest('.ez-multi-choice-box').addClass('poped');
                            elt.closest('.ez-multi-choice-box').find('.ez-btn.cancel').focus();
                            $(this).addClass('focus-on');
                            //Si la boîte déborde sur la droite
                            var to_left = $(window).width() - (elt.closest('.ez-multi-choice-box').offset().left + elt.closest('.ez-multi-choice-box').find('.choice-popup').width())
                            if (to_left < 0) {
                                to_left -= 3;
                                elt.closest('.ez-multi-choice-box').find('.choice-popup').css('left', to_left);
                            }
                        }
                    }).on('mousedown keydown', function (e) {
                        e.preventDefault();
                    });

                    elt.closest('.ez-multi-choice-box').find('.ez-btn.cancel').click(function () {
                        //On remet tout dans l'état à l'ouverture
                        elt.find('input:not(.pre-checked)').prop('checked', false);
                        elt.find('input.pre-checked').prop('checked', true).removeClass('pre-checked');
                        elt.find('input:first-child').change();//Déclenché pour cocher/décocher (Tout selectionner)
                        elt.closest('.ez-multi-choice-box').removeClass('poped');
                        elt.closest('td').find('select.multi-choice').removeClass('focus-on');
                        //Si la boîte avait débordé sur la droite et qu'on lui a ajouté du css, on l'enlève
                        elt.closest('.ez-multi-choice-box').find('.choice-popup').css('left', "");
                    });
                    //Recherche via le <select>
                    elt.closest('.ez-multi-choice-box').find('.ez-btn.validate').on('click', function () {
                        var inputs = elt.find('input');
                        var checked = [];
                        var search_string;
                        $.each(inputs, function () {
                            if ($(this).is(":checked"))
                                checked.push('^' + $.fn.dataTable.util.escapeRegex($(this).val()) + '$');
                        });

                        //On applique le filtre
                        if (checked.length !== inputs.length) {
                            search_string = checked.join('|');
                            $(this).closest('td').find('select').addClass('filter-on');
                        } else {
                            search_string = "";
                            $(this).closest('td').find('select').removeClass('filter-on');
                        }
                        column.search(search_string, true, false).draw();

                        //Fermeture du popup
                        elt.find('input').removeClass('pre-checked').change();
                        elt.closest('.ez-multi-choice-box').removeClass('poped');
                        $(this).closest('td').find('select').removeClass('focus-on');
                        //Si la boîte avait débordé sur la droite et qu'on lui a ajouté du css, on l'enlève
                        elt.closest('.ez-multi-choice-box').find('.choice-popup').css('left', "");
                    });
                    //
                    elt.closest('.ez-multi-choice-box').find('input.search').on("keyup change focus blur", function () {
                        el_tech_ezfilter_search(this);
                    });
                    //
                    elt.closest('.ez-multi-choice-box').find('input.check-all').on('change click', function () {
                        if ($(this).is(':checked'))
                            elt.find('input').prop('checked', true);
                        else
                            elt.find('input').prop('checked', false);

                        if (elt.find('input:checked').length === 0)
                            elt.closest('.ez-multi-choice-box').find('.ez-btn.validate').prop('disabled', true);
                        else
                            elt.closest('.ez-multi-choice-box').find('.ez-btn.validate').prop('disabled', false);
                    });
                    //
                    elt.find('input').on('change click', function () {
                        if (elt.find('input').length === elt.find('input:checked').length)
                            elt.closest('.ez-multi-choice-box').find('input.check-all').prop('checked', true);
                        else
                            elt.closest('.ez-multi-choice-box').find('input.check-all').prop('checked', false);
                        //
                        if (elt.find('input:checked').length === 0)
                            elt.closest('.ez-multi-choice-box').find('.ez-btn.validate').prop('disabled', true);
                        else
                            elt.closest('.ez-multi-choice-box').find('.ez-btn.validate').prop('disabled', false);
                    });
                }
                //Il y a au moins un filtre pour cette colonne
                there_is_filter = true;
            }
        });

        //Intégration de la ligne de filtre au <table> dataTable
        table.find('thead tr.ez-filter').remove(); //On supprime le filtre précédent s'il en existe
        //
        if (there_is_filter)
            table.find('thead').append(tr); //Intégration du nouveau filter à la table

        //
    });

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * DataTables API
     *
     * For complete documentation, please refer to the docs/api directory or the
     * DataTables site
     */
    $.fn.dataTable.Api.register('column().ez_options()', function () {
        var options = [];
        //
        this.cache('search').sort().unique().each(function (d) {
            options[d] = d;
        });
        //
        return options;
    });


    /***********************************
     * OTHER FUNCTIONS
     */
    /**
     * Flitrer la liste des options des <select> pour le multi-choice
     * @param {type} input
     * @returns {undefined}
     */
    function el_tech_ezfilter_search(input) {
        // Declare variables
        var filter, ul, li, a, i, txtValue;
        input = $(input);
        filter = input.val().toUpperCase();
        ul = input.closest('.choice-popup').find('.choice-list');
        li = ul.find('.ez-check-line');

        // Loop through all list items, and hide those who don't match the search query
        for (i = 0; i < li.length; i++) {
            txtValue = li[i].textContent || li[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }

}));


