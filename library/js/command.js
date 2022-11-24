/**
 * Initialisation de datatable
 * @param {type} id
 * @param {type} options
 * @returns {undefined}
 */
function setupDataTables(id, options) {
    //Initialisation des arguments ----------------------------
    //Objet JQuery du <table>
    var tblelt = $("#" + id);
    //
    atDataTableSearch = null; //Compte à rebours pour la recherche
    atDataTableColSearch = []; //Compte à rebours pour la recherche sur colonnes
    //
    /*var responsiveHelper = undefined;
     var breakpointDefinition = {
     tablet: 1024,
     phone: 480
     };*/
    //
    options = undefined === options ? {} : options;
    //Paramètres de numérotation automatique des lignes ----------
    //Par defaut 'true'
    options.autoNumbering = undefined === options.autoNumbering ? true : options.autoNumbering;
    //Pas de numérotation si aucune colonne de numérotation n'est réservée
    var num_th = tblelt.find("thead > tr:first-child > th:first-child");
    if (num_th.length && num_th.text().trim() == "#") {
        //Pas de tri sur la colonne de numérotation
        var col_def = {
            "searchable": false,
            "orderable": false,
            "targets": 0
        }
        if (typeof options.columnDefs === "object")
            options.columnDefs.push(col_def)
        else
            options.columnDefs = [col_def];
        //On enlève le #
        num_th.html("");
        //
    } else
        options.autoNumbering = false;

    //
    //Fusion des options    -------------------------
    var toptions = {
        //"bJQueryUI": true,
        "bStateSave": true,
        "sPaginationType": "full_numbers",
        "oLanguage": {
            "sUrl": "library/dataTables_1.10/translations/fr.json"
        },
        "aLengthMenu": [10 ,20, 40, 60, 80, 100],
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
        /* fnPreDrawCallback: function() {
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
         }*/
    };
    //Fusion des options
    jQuery.extend(toptions, options);

    /**
     * 
     * @type @exp;tblelt@call;dataTable|@exp;tblelt@call;dataTableFiltres d'entete de liste
     */


    /**
     * (Partie à SUPPRIMER PEUT ÊTRE)
     * Ajout de Filtre sur une colonne particulière
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
                //alert("init");
                //Ajout du champs input text de recherche
                var label_block = '<label>' + input_lib + ' :<input class="Input' + id_search + '" aria-controls="fleetinfoglance-op" type="text"></label>';
                var filter_div = $("#" + id + "_wrapper div.dataTables_filter");
                if (filter_div.length) {
                    filter_div.append(label_block);
                } else {
                    $('<div id="Box' + id_search + '" data-enhance="false">\
                    <div class="dataTables_length">&nbsp;</div>\
                    <div class="dataTables_filter" id="vhl_code_filter">\
                        ' + label_block + '\
                    </div>\
                    </div>').insertBefore(tblelt);
                }
                //Gestion de l'événement de saisi dans le champs input text de recherche
                $("#" + id + "_wrapper .Input" + id_search).on("keyup", function() {
                    //Annulation du précédent compte à rebours de recherche
                    if (atDataTableSearch)
                        clearTimeout(atDataTableSearch);
                    //
                    var searched = $(this).val();
                    //
                    atDataTableSearch = setTimeout(function() {
                        if (search_start_with === true)
                            dtable.column(col_ind).search('^' + searched, true).draw();
                        else
                            dtable.column(col_ind).search(searched).draw();
                    }, 600)
                });
            });
        }//if
    }//if


    /**
     * Filtres par colonne
     */
    if (typeof options.atColumnFilter === 'object' || tblelt.find('thead tr th[at-dt-filter]').length > 0) {
        //On déplace le lien de trie datatable sur les cellules les plus hautes de <thead> pour chaque colonne
        toptions.orderCellsTop = true;
        //
        //Après initialisation du datatable
        tblelt.on('init.dt', function() {
            //Liste des paramètres pour chaque colonne pour la création du filtre
            var filterOpts = [];
            dtable.columns().every(function() {
                filterOpts[this.index()] = null;
                atDataTableColSearch[this.index()] = false;
                console.log($(this.nodes().to$()[0]).attr('class'))
                /*this.nodes().forEach(function(node){
                 console.log(node)
                 })*/
            });

            //Traitement des Paramètres de filtre passés à setupDatatables()
            //Pour chaque objet de paramètres
            for (var p in options.atColumnFilter) {
                var opt = options.atColumnFilter[p];
                var f_opt = {}; //Option de filtre
                f_opt.type = undefined === opt.type ? 'input' : opt.type; //Type input|select
                f_opt.wordBeginsWith = undefined === opt.wordBeginsWith ? true : opt.wordBeginsWith;
                f_opt.options = undefined === opt.options ? null : opt.options; //Tableaud des <option> de <select>
                //On affect les paramètres à chaque colonne concernée
                if (Array.isArray(opt.colIndexes)) {
                    opt.colIndexes.forEach(function(val) {
                        //S'il y a un object list de <option> pour chaque colonne
                        if (typeof val === 'array' && f_opt.type === 'select') {
                            f_opt.options = val[1]; //val[1] est la liste de valeurs pour les <option>
                            filterOpts[val[0]] = f_opt; //val[0] est l'index
                        } else //Sinon on affecte les paramètre à  la colonne simplement
                            filterOpts[val] = f_opt;
                    })
                }
            }

            //Traitement des Paramètres de filtre définis par l'attribut "at-dt-filter" des th de l'entête
            dtable.columns().every(function() {
                var attr = $(this.header()).attr('at-dt-filter');
                //
                if (attr !== undefined && attr !== false && attr !== null) {
                    var f_opt = {type: 'input', wordBeginsWith: true, options: null}; //Valeur par defaut
                    //at-dt-filter est sous la forme : type=input|wordBeginsWith=false|options=options_object
                    attr = attr.split('|');
                    for (p in attr) {
                        var opt = attr[p].split('=');
                        if (opt[0] == 'type')
                            f_opt.type = opt.length > 1 ? opt[1] : f_opt.type;
                        if (opt[0] == 'wordBeginsWith')
                            f_opt.wordBeginsWith = opt.length > 1 ? eval(opt[1]) : f_opt.type;
                        if (opt[0] == 'options') {
                            if (opt.length > 1) {
                                f_opt.options = opt[1].search('{') > -1 ? jQuery.parseJSON(opt[1]) : eval(opt[1]);
                            }
                        }
                    }
                    //Affectation des paramètres à la colonne
                    filterOpts[this.index()] = f_opt;
                }
            });

            //Ligne de filtre
            var tr = $("<tr class='filter-dt'>");

            //Création de filtre pour chaque colonne du tableau
            dtable.columns().every(function() {
                var column = this;
                var ind = column.index();
                var col_opt = filterOpts[column.index()]; //Paramètres pour la création de filtre
                var th = $("<td></td>");
                tr.append(th);
                //S'il y a des paramètres pour cette colonne on crée le filtre
                if (col_opt !== null && typeof col_opt === "object") {
                    var elt = null;
                    //Filtre de type <input>
                    if (col_opt.type == 'input') {
                        elt = $("<input type='text' data-role='none' data-enhance='false' />"); //L'input
                        elt.on('keyup change clear', function() { //Evénement déclenchant le filtre
                            if (column.search() !== elt.val()) {
                                //Annulation du précédent compte à rebours de recherche
                                if (atDataTableColSearch[ind])
                                    clearTimeout(atDataTableColSearch[ind]);
                                //
                                atDataTableColSearch[ind] = setTimeout(function() { //Compte à rebours pour lancer la recherche
                                    //Si le contenu de la cellule doit commencer par le mot recherché
                                    if (col_opt.wordBeginsWith === true) {
                                        column.search('^' + $.fn.dataTable.util.escapeRegex(elt.val()), true).draw();
                                    } else //Si le contenu de la cellule doit juste contenir le mot recherché
                                        column.search(elt.val()).draw();
                                    //Un peut de css
                                    elt.val() == "" ? elt.removeClass('filter-on') : elt.addClass('filter-on');
                                }, 250);
                            }
                        });
                    } else
                    //Filtre de type <select>
                    if (col_opt.type == 'select') {
                        elt = $("<select data-role='none' data-enhance='false'><option value='' ></option></select>");
                        //Contenu du select ----
                        //Si la liste des <option> est passée en paramètres
                        if (col_opt.options !== null && typeof col_opt.options === 'object') {
                            for (var p in col_opt.options) {
                                elt.append($('<option value="' + p + '">' + col_opt.options[p] + '</option>'));
                            }
                        } else { //Sinon on crée la liste à partir des données de la colonne 
                            var cells = column.nodes().to$();
                            var has_orthogonal = cells.length > 0 && ($(cells[0]).attr('data-filter') === undefined || $(cells[0]).attr('data-search') === undefined);

                            column.cache('search').sort().unique().each(function(text) {
                                if (text !== ""){
                                    elt.append($('<option value="' + text + '">' + text + '</option>'));
                                }
                            });
                            /*$.fn.dataTable.ext.search.push(
                             function(settings, searchData, index, rowData, counter) {
                             console.log(settings)
                             //console.log(index)
                             //console.log(rowData)
                             //console.log(counter)
                             }
                             );*/
                        }//
                        //Recherche via le <select>
                        elt.on('change', function() {
                            if (column.search() !== elt.val()) {
                                var search_string = elt.val() == "" ? elt.val() : '^' + $.fn.dataTable.util.escapeRegex(elt.val()) + '$';
                                column.search(search_string, true).draw();
                                //Un peut de css
                                elt.val() == "" ? elt.removeClass('filter-on') : elt.addClass('filter-on');
                            }
                        });
                    }
                    //Affectation du filtre à la cellule de <thead>
                    if (elt !== null)
                        th.append(elt);
                }
            });
            //Intégration de la ligne de filtre au <table> dataTable
            tblelt.find('thead').append(tr).trigger('draw.dt');
        })
    }




    /**
     * Initialisation
     * @type @exp;tblelt@call;dataTable
     */
    var dtable = tblelt.DataTable(toptions);
    /**
     * 
     */


    /**
     * Numérotion automatique des lignes
     */
    if (options.autoNumbering == true) {
        //On active la numérotation automatique
        dtable.on('order.dt search.dt', function() {
            dtable.column(0, {search: 'applied', order: 'applied'}).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }).on('draw.dt', function() { //Pour JQuery Mobile
            $(this).trigger('create')
        }).draw();
    }

    //
    return dtable;
}