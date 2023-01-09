<?php
include_once 'loader.php';

if($_SESSION['idCptVend'] != 1)
    $listcomptes =  Compte::lineOfCompte($_SESSION['idCptVend']);
else
    $listcomptes =  Compte::listOfCompte();

?>
<div id="all" style="width:98%; margin:auto;">
    <div id="content">
        <!--<div style='text-align:center; margin-top:5px;'>
            <a href="form/addUser.php" data-rel="dialog" data-transition = "flip" data-role='button' data-mini='true' data-icon='plus' data-inline='true' data-theme='b'>Ajout Utilisateur</a>
        </div>-->
        <div id="list_comptes" style="margin-top: 20px;">
            <table class="table table-striped table-bordered table-hover" id="tablistusers">
                <thead>
                    <tr class="th_color">
                        <th rowspan="2" style="width: 2%;">#</th>
                        <th rowspan="2" <?= $dt_filter_input ?>>Nom</th>
                        <th rowspan="2" <?= $dt_filter_input ?>>Slogan</th>
                        <th rowspan="2" <?= $dt_filter_input ?>>Tel</th>
                        <th rowspan="2" <?= $dt_filter_input ?>>Email</th>
                        <th rowspan="2" <?= $dt_filter_input ?>>Adresse</th>
                        <th rowspan="2" <?= $dt_filter_input ?>>Description</th>
                        <th rowspan="2" <?= $dt_filter_input ?>>Catégories</th>
                        <th rowspan="2" <?= $dt_filter_select ?>>Etat</th>
                        <th colspan="2" style="width:8%;" >Actions</th>
                    </tr>
                    <tr class="th_color">
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listcomptes as $line) : 
                        //$style_css = in_array($line['typeUser'], array("developper","superadmin")) ? "style=\"cursor: default; pointer-events: none; text-decoration: none; color: grey;\"" : "";

                        if($line['etatCptVend'] == 1){
                            $etat = "Active";
                            $lien = "<a $delete_css href=\"form/deleteCompte.php?desactive=true&idcptvend={$line['idCptVend']}\" $delete_icon_attr>$TITLE_DESACTIVE</a>";
                        }else{
                            $etat = "Desactive";
                            $lien = "<a $delete_css href=\"form/deleteCompte.php?active=true&idcptvend={$line['idCptVend']}\" $active_icon_attr>$TITLE_ACTIVE</a>";
                        }

                        $cat = Compte::listCategorieCompte($line['idCptVend']);

                        $delete_css = ($_SESSION['idCptVend'] !=1) ? "style=\"cursor: default; pointer-events: none; text-decoration: none; color: grey;\"" : "";
                        $update_css = ($_SESSION['idCptVend'] ==1 && $line['idCptVend'] != 1) ? "style=\"cursor: default; pointer-events: none; text-decoration: none; color: grey;\"" : "";
                        ?>
                        <tr>
                            <td></td>
                            <td><?= $line['nomCptVend'] ?></td>
                            <td><?= $line['sloganCptVend'] ?></td>
                            <td><?= $line['telCptVend'] ?></td>
                            <td><?= $line['emailCptVend'] ?></td>
                            <td><?= $line['adresCptVend'] ?></td>
                            <td><?= nl2br($line['descCptVend'])  ?></td>
                            <td><?= $cat ?></td>
                            <td><?= $etat ?></td>
                            <td><a <?= $update_css ?> href='form/editCompte.php?modif=true&general=true&idcptvend=<?= $line['idCptVend'] ?>'  <?= $edit_icon_attr ?>><?= $TITLE_MODIF_INFOS ?></a></td>
                            <td><?=$lien?></td>
                        </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>
            <style>
                select{
                    color: #000000;
                }
                .cache, .cache_client {display: none;}
                .greydiv{cursor: default; pointer-events: none; text-decoration: none; color: grey;}
                .ui-mobile-viewport-transitioning, .ui-mobile-viewport-transitioning .ui-page {
                    width: 100%;
                    height: 100%;
                    overflow: auto;
                }
                .formLine60{
                    margin-right: 5px;
                }
                .ui-select {
                    top: 0px;
                }
            </style>
            <script type="text/javascript">
                $(document).ready(function () {
                    listuser = setupDataTables("tablistusers", {
                        dom: 'Bfrtip',
                        "columnDefs": [
                            {
                                sortable: false,
                                orderable: false,
                                targets: [0]
                            }
                        ],
                        buttons: [
                            {
                                extend: 'pageLength',
                            },
                            {
                                extend: 'copyHtml5',
                                text: '<i class="fa fa-file-excel-o">Copy</i>',
                                titleAttr: 'Copy',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4]
                                }
                            },
                            {
                                extend: 'excelHtml5',
                                text: '<i class="fa fa-file-excel-o">Excel</i>',
                                titleAttr: 'Excel',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4]
                                }
                            },
                            {
                                extend: 'csvHtml5',
                                text: '<i class="fa fa-file-text-o">CSV</i>',
                                titleAttr: 'CSV',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4]
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                text: '<i class="fa fa-file-pdf-o">PDF</i>',
                                titleAttr: 'PDF',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4]
                                }
                            }
                        ],
                    });
                })
            </script>
        </div>
    </div>
</div>