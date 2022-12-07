<?php 
include_once '../loader.php';
//
$liste_banniere = Banniere::listBanniere($_SESSION['idCptVend']);

$maw_width = "max-width:150px";
?>
<h3 style="text-align: center;">LA BANNIERE</h3>
<table class="table table-striped table-bordered table-hover" id="listban">
    <thead>
        <tr class="th_color">
            <th rowspan="2" style="width: 4%;">#</th>
            <th rowspan="2" <?= $dt_filter_input ?>>Photo</th>
            <th rowspan="2" <?= $dt_filter_input ?>>Compte</th>
            <th colspan="2" style="width: 8%;">Actions</th>
        </tr>
        <tr class="th_color">
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($liste_banniere as $line) {
            $camera = empty($line["lienBanCpt"]) ? "" : "<img src='" . $line["lienBanCpt"] . "' style='width:100%;$maw_width'/>";
            $title = empty($line["nomCptVend"]) ? "" : $line["nomCptVend"];
        ?>
            <tr>
                <td></td>
                <td><?= $camera ?></td>
                <td><?= $title ?></td>
                <td><a class="editbanniere" href='#' idban="<?= $line['idBanCpt'] ?>" imgs="<?=$camera?>" lienban="<?= $line['lienBanCpt'] ?>" <?= $edit_icon_attr ?>><?= $TITLE_MODIF_INFOS ?></a></td>
                <td><a <?= $style_css ?> href="form/deleteBanniere.php?delete=true&idban=<?= $line['idBanCpt'] ?>" <?= $delete_icon_attr ?>><?= $TITLE_DELETE ?></a></td>
            </tr>

        <?php } ?>
    </tbody>
</table>
<style>
    table#listban td img:hover {
        transform: scale(2);
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {

        //Gestion modification
        $(".editbanniere").on('click', function(event) {
            var idban = $(this).attr("idban");
            var lienban = $(this).attr("lienban");
            var imgs = $(this).attr("imgs");
            
            $(".lastimage").html(imgs).trigger('create');
            $(".addtitle").html("Modifier cette Banniere").trigger('create');
            $("#id_ban").val(idban).trigger('create');
            $("#lien_ban").val(lienban).trigger('create');

            return false;
        })

        //Edit de compte
        bannieretable = setupDataTables("listban", {
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
                        columns: [0, 1]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o">Excel</i>',
                    titleAttr: 'Excel',
                    exportOptions: {
                        columns: [0, 1]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="fa fa-file-text-o">CSV</i>',
                    titleAttr: 'CSV',
                    exportOptions: {
                        columns: [0, 1]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o">PDF</i>',
                    titleAttr: 'PDF',
                    exportOptions: {
                        columns: [0, 1]
                    }
                }
            ],
        });
    })
</script>