<?php
include_once 'loader.php';

$listcategory = Category::listCategory();  //User::listForAllUsers($_SESSION['idCptVend']);

if (isset($submitcat)) {

    if (empty($lib_cat) || !count($lib_cat)) {

        $error = sms_error("Veuillez remplir le libelle");

        $response['feedback'] = $error;

    } else {

        $donnOne = [];
        $donnTwo = [];

        $orther = $lib_cat;

        for ($i = 0; $i < count($orther); $i++) {

            for ($j = 0; $j < count($lib_cat); $j++) {
                $cpt = 0;
                if ($orther[$i] == $lib_cat[$j]) {
                    $cpt++;
                    if ($cpt == 1) {
                        $donnOne[] = $lib_cat[$j];
                        $donnTwo[] = $desc_cat[$j];
                    }
                }
            }
        }
        
        for ($count = 0; $count < count($donnOne); $count++) {

            if (!GeneralClass::checkDoublons($donnOne[$count], "categorie_vente", "libCatVent"))
                Category::insertCategory($donnOne[$count], $donnTwo[$count]);
        }

        $success = $save_success_msg;
        //Pas d'Erreur d'insertion, le script continue
        $response['feedback'] = 0;
        $response['response'] = $success;
    }

    print json_encode($response);
    exit();
}

?>
<div id="all" style="width:98%; margin:auto;">
    <div id="content">
        <?php if ($_SESSION['idCptVend'] == 1) { ?>
            <div style='text-align:center; margin-top:5px;'>
                <a href="#" id="ajout_cat" data-role='button' data-mini='true' data-icon='plus' data-inline='true' data-theme='b'>Ajout Catégories</a>
            </div>
        <?php } ?>
        <div id="contentajoutcat" class="cache" style="width: 70%; margin:auto; border: 1px solid grey; border-radius: 5px;">
            <div id="" style="margin:10px 0px; width: 100%;">
                <form method="post" name="addcat" id="addcat">
                    <table style="width:98%; margin: 10px auto 30px auto;">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Libelle</th>
                                <th style="width: 55%;">Description</th>
                                <th style="width: 5%;">
                                    <a href="#" data-role='button' data-mini='true' class="add_cat" id="add_cat" data-icon='plus' data-inline='true' data-theme='b'>Ajouter</a>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="item_table">

                        </tbody>
                    </table>
                    <div style="width:50%; margin: auto;">
                        <div class="messageBox" style="height: auto;"></div>
                        <div class="separator" style="margin-top : 30px"></div>
                        <div id="ajaxloader" style="margin:8px auto"></div>
                        <input type="hidden" name="submitcat" value="submitcat" />
                        <div id="actual" style="display:none;"><input type="reset" value="Reset" id="actualiseForm"></div>
                        <input type="submit" id="uploadSubmit" value="Enregistrer" data-theme='b' data-mini='true' data-icon='check' />
                        <div id="targetLayer" style="display:none;"></div>
                    </div>
                </form>
            </div>
        </div>
        <div id="list_category" style="margin-top: 20px;">
            <table class="table table-striped table-bordered table-hover" id="tablistcategories">
                <thead>
                    <tr class="th_color">
                        <th rowspan="2" style="width: 2%;">#</th>
                        <th rowspan="2" <?= $dt_filter_input ?>>libelle</th>
                        <th rowspan="2" <?= $dt_filter_input ?>>Description</th>
                        <th colspan="2" style="width:8%;">Actions</th>
                    </tr>
                    <tr class="th_color">
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listcategory as $line) :

                        $griser_css = ($_SESSION['idCptVend'] != 1) ? "style=\"cursor: default; pointer-events: none; text-decoration: none; color: grey;\"" : "";
                    ?>
                        <tr>
                            <td></td>
                            <td><?= $line['libCatVent'] ?></td>
                            <td><?= nl2br($line['descCatVent']) ?></td>
                            <td><a <?= $griser_css ?> href='form/editCategory.php?modif=true&general=true&idcatvent=<?= $line['idCatVent'] ?>' <?= $edit_icon_attr ?>><?= $TITLE_MODIF_INFOS ?></a></td>
                            <td><a <?= $griser_css ?> href="form/deleteCategory.php?delete=true&idcatvent=<?= $line['idCatVent'] ?>" <?= $delete_icon_attr ?>><?= $TITLE_DELETE ?></a></td>
                        </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>
            <style>
                select {
                    color: #000000;
                }

                .cache,
                .cache_client {
                    display: none;
                }

                .greydiv {
                    cursor: default;
                    pointer-events: none;
                    text-decoration: none;
                    color: grey;
                }

                .ui-mobile-viewport-transitioning,
                .ui-mobile-viewport-transitioning .ui-page {
                    width: 100%;
                    height: 100%;
                    overflow: auto;
                }

                .formLine60 {
                    margin-right: 5px;
                }

                .ui-select {
                    top: 0px;
                }
            </style>
            <script type="text/javascript">
                var nb = 0;
                $(document).ready(function() {
                    //Ouverture du formulaire
                    $("#ajout_cat").click(function() {
                        $("#contentajoutcat").toggleClass('cache');
                        if (!$("#contentajoutcat").hasClass('cache'))
                            $('#add_cat').click();
                        else
                            $(".remove").each(function() {
                                $(this).closest('tr').remove();
                            });
                    });

                    $('#add_cat').on('click', function() {

                        var html = '';
                        html += '<tr>';
                        html += '<td><div data-role="fieldcontain" class="formLine60"><label for="lib_cat' + nb + '">Titre:</label><input type="text" name="lib_cat[]" id="lib_cat' + nb + '" maxlength="256" value="" class="text ui-corner-tl" data-mini="true" /></div></td>';
                        html += '<td><div data-role="fieldcontain" class="formLine60"><label for="desc_cat' + nb + '" class="affect-css">Description:</label><textarea type="textarea" name="desc_cat[]" id="desc_cat' + nb + '"></textarea></div></td>';
                        html += '<td><a href="#" style="background-color:#dc3545; margin:20%;" data-role="button" data-mini="true" class="remove" id="remove" data-icon="minus" data-inline="true"><span style="color:#dc3545;">Retirer</span></a></td>';
                        html += '</tr>';
                        $('#item_table').append(html).trigger('create');
                        nb++;
                    });


                    $(document).on('click', '.remove', function() {
                        $(this).closest('tr').remove();
                    });


                    $('#addcat').submit(function(event) {
                        params = $("#addcat").serialize();
                        AjaxLoader("category.php", params + '&submit=yes', $('#addcat .messageBox'), function() {
                            setTimeout(function() {
                                $.mobile.loading("hide");
                                $("#content").load('category.php', function() {
                                    $(this).trigger('create');
                                });
                                //$('#addcat .messageBox').html("").trigger("create");
                                //$("#createcompte #id_client").html("").trigger("chosen:updated");
                                //$("#addcat #actualiseForm").click();
                                /*$(".remove").each(function() {
                                    $(this).closest('tr').remove();
                                });*/
                            }, 4000);
                            //return false;
                        });
                        return false;
                    });

                    listcategory = setupDataTables("tablistcategories", {
                        dom: 'Bfrtip',
                        "columnDefs": [{
                            sortable: false,
                            orderable: false,
                            targets: [0]
                        }],
                        buttons: [{
                                extend: 'pageLength',
                            },
                            {
                                extend: 'copyHtml5',
                                text: '<i class="fa fa-file-excel-o">Copy</i>',
                                titleAttr: 'Copy',
                                exportOptions: {
                                    columns: [0, 1, 2]
                                }
                            },
                            {
                                extend: 'excelHtml5',
                                text: '<i class="fa fa-file-excel-o">Excel</i>',
                                titleAttr: 'Excel',
                                exportOptions: {
                                    columns: [0, 1, 2]
                                }
                            },
                            {
                                extend: 'csvHtml5',
                                text: '<i class="fa fa-file-text-o">CSV</i>',
                                titleAttr: 'CSV',
                                exportOptions: {
                                    columns: [0, 1, 2]
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                text: '<i class="fa fa-file-pdf-o">PDF</i>',
                                titleAttr: 'PDF',
                                exportOptions: {
                                    columns: [0, 1, 2]
                                }
                            }
                        ],
                    });
                })
            </script>
        </div>
    </div>
</div>