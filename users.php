<?php
include_once 'loader.php';

$listuser =  User::listForAllUsers($_SESSION['idCptVend']);
?>
<div id="all" style="width:98%; margin:auto;">
    <div id="content">
        <div style='text-align:center; margin-top:5px;'>
            <a href="form/addUser.php" data-rel="dialog" data-transition = "flip" data-role='button' data-mini='true' data-icon='plus' data-inline='true' data-theme='b'>Ajout Utilisateur</a>
        </div>
        <div id="list_users" style="margin-top: 20px;">
            <table class="table table-striped table-bordered table-hover" id="tablistusers">
                <thead>
                    <tr class="th_color">
                        <th rowspan="2" style="width: 2%;">#</th>
                        <th rowspan="2" <?= $dt_filter_input ?>>Nom</th>
                        <th rowspan="2" <?= $dt_filter_input ?>>Prenoms</th>
                        <th rowspan="2" <?= $dt_filter_input ?>>Tel</th>
                        <th rowspan="2" <?= $dt_filter_input ?>>Email</th>
                        <th colspan="4" style="width:8%;" >Actions</th>
                    </tr>
                    <tr class="th_color">
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listuser as $line) : 
                        //$style_css = in_array($line['typeUser'], array("developper","superadmin")) ? "style=\"cursor: default; pointer-events: none; text-decoration: none; color: grey;\"" : "";

                        $style_css = ($_SESSION['idCptVend'] ==1 && $line['idCptVend'] != 1) || in_array($line['typeUser'], array("developper","superadmin")) ? "style=\"cursor: default; pointer-events: none; text-decoration: none; color: grey;\"" : "";
                        $griser_css = ($_SESSION['idCptVend'] ==1 && $line['idCptVend'] != 1) ? "style=\"cursor: default; pointer-events: none; text-decoration: none; color: grey;\"" : "";
                        ?>
                        <tr>
                            <td></td>
                            <td><?= $line['nomUser'] ?></td>
                            <td><?= $line['prenomUser'] ?></td>
                            <td><?= $line['telUser'] ?></td>
                            <td><?= $line['emailUser'] ?></td>
                            <td><a <?= $griser_css ?> class="editpwd" href='form/editPassword.php?modif=true&general=true&iduser=<?= $line['idUser'] ?>'  <?= $edit_icon_attr ?>><?= $ATR_PASSWORD ?></a></td>
                            <td><a <?= $griser_css ?> class="edituser" href='form/editUser.php?modif=true&general=true&iduser=<?= $line['idUser'] ?>'  <?= $edit_icon_attr ?>><?= $TITLE_MODIF_INFOS ?></a></td>
                            <td><a class="action" <?= $style_css ?> href='form/affectMenu.php?affect=true&iduser=<?= $line['idUser'] ?>' <?= $affect_icon_attr ?>><?= $TITLE_AFFEC_DR ?></a></td>
                            <td><a <?= $style_css ?> href="form/deleteUser.php?delete=true&iduser=<?= $line['idUser'] ?>" <?= $delete_icon_attr ?>><?= $TITLE_DELETE ?></a></td>
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