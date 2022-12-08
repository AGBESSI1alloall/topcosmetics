<?php
include_once '../loader.php';

$listproduit = ProduitImages::listProd($_SESSION['idCptVend']);

$idproduit = isset($idproduit) ? $idproduit : 0;
?>
<h3 style="text-align: center;">Liste des Produits</h3>
<table class="table table-striped table-bordered table-hover" id="listproduit">
    <thead>
        <tr class="th_color">
            <th rowspan="2" style="width: 2%;">#</th>
            <th rowspan="2" <?= $dt_filter_select ?>>Nom</th>
            <th rowspan="2" <?= $dt_filter_input ?>>Catégorie</th>
            <th rowspan="2" <?= $dt_filter_input ?>>Description</th>
            <th rowspan="2" <?= $dt_filter_input ?>>Prix Normal</th>
            <th rowspan="2" <?= $dt_filter_input ?>>Prix Promo</th>
            <th rowspan="2" <?= $dt_filter_input ?>>Nb Images</th>
            <th colspan="4" style="width: 8%;">Actions</th>
        </tr>
        <tr class="th_color">
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($listproduit as $line) {

            $nb_prod = ProduitImages::nbImgProd($line['idProd']);
            //$delete_style = $nb >= 1 && $line['ext_chap'] == 'pdf' ? "style=\"cursor: default; pointer-events: none; text-decoration: none; color: grey;\"" : "";
            //$view_file = $nb < 1 ? "style=\"cursor: default; pointer-events: none; text-decoration: none; color: grey;\"" : "";

        ?>
            <tr>
                <td></td>
                <td><?= $line['nomProd'] ?></td>
                <td><?= $line['libCatVent'] ?></td>
                <td><?= nl2br($line['descProd']) ?></td>
                <td><?= $line['prixProd'] ?></td>
                <td><?= $line['prixPromoProd'] ?></td>
                <td><?= $nb_prod ?></td>
                <td><a class="viewfiles" href='#' idproduit="<?= $line['idProd'] ?>" nbimg="<?= $nb_prod ?>" <?= $detail_icon_attr ?>><?= $TITLE_INFOS_DETAIL ?></a></td>
                <td><a href='#' class="affectfile" idproduit="<?= $line['idProd'] ?>" nomproduit="<?= $line['nomProd'] ?>" nbimg="<?= $nb_prod ?>" <?= $affect_icon_attr ?>><?= titles("Affecter des Images") ?></a></td>
                <td><a class="editproduit" href='#' idproduit="<?= $line['idProd'] ?>" nomprod="<?= $line['nomProd'] ?>" catprod="<?= $line['idCatVent'] ?>" descprod="<?= nl2br($line['descProd']) ?>" prixprod="<?= $line['prixProd'] ?>" prixpromoprod="<?= $line['prixPromoProd'] ?>" <?= $edit_icon_attr ?>><?= $TITLE_MODIF_INFOS ?></a></td>
                <td><a href="form/deleteProduit.php?delete=true&idproduit=<?= $line['idProd'] ?>" <?= $delete_icon_attr ?>><?= $TITLE_DELETE ?></a></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<style>
</style>
<script type="text/javascript">
    $(document).ready(function() {

        idproduit = "<?= $idproduit ?>";


        function recharge_file_produit(idproduit) {
            $.mobile.loading('show');
            var ct = '<?= SITE_URL ?>loadFile/listProduitFiles.php';
            $('#list_produit_img').empty().load(ct, {
                    idproduit: idproduit
                },
                function(html) {
                    $(this).html(html).trigger('create');
                    $.mobile.loading('hide');
                });
        }

        if (parseInt(idproduit) != 0)
            recharge_file_produit(idproduit)

        $(".viewfiles").on('click', function(e) {
            var idproduit = $(this).attr("idproduit");
            recharge_file_produit(idproduit)
            return false;
        });

        //Affectation des fichiers au chapitre
        $(".affectfile").on('click', function(event) {
            var idproduit = "";
            var nomproduit = "";
            var nbimg = "";

            if (!$("#contentajoutfile").hasClass('cache')) {
                $("#contentajoutfile").addClass('cache');
                idproduit = "";
                nomproduit = "";
                nbimg = "";
                $('#list_produit_img').empty();

            } else {
                $("#contentajoutfile").removeClass('cache');
                $("#contentajoutprod").addClass('cache');
                idproduit = $(this).attr("idproduit");
                nomproduit = $(this).attr("nomproduit");
                nbimg = $(this).attr("nbimg");
                recharge_file_produit(idproduit);
            }

            $("#id_produit_file").val(idproduit).trigger('create');
            $("#nom_produit").val(nomproduit).trigger('create');
            $("#nb_prod").val(nbimg).trigger('create');
        });

        //Gestion modification
        $(".editproduit").on('click', function(event) {
            var idproduit = $(this).attr("idproduit");
            var nomproduit = $(this).attr("nomprod");
            var catproduit = $(this).attr("catprod");
            var descproduit = $(this).attr("descprod");
            var prixproduit = $(this).attr("prixprod");
            var prixpromoproduit = $(this).attr("prixpromoprod");
            //ouverture du formulaire
            if (!$("#contentajoutprod").hasClass('cache')) {

            } else {
                $("#addprodfile #actualiseForm").click();
                $("#contentajoutfile").addClass('cache');
                $("#contentajoutprod").removeClass('cache');
            }
            //Affectation des données au form
            $("#id_prod").val(idproduit).trigger('create');
            $("#cat_prod").val(catproduit).trigger('chosen:updated');
            $("#nom_prod").val(nomproduit).trigger('create');
            $("#desc_prod").val(descproduit).trigger('create');
            $("#prix_prod").val(prixproduit).trigger('create');
            $("#prix_promo_prod").val(prixpromoproduit).trigger('create');
            return false;
        })

        //Edit de compte
        produittable = setupDataTables("listproduit", {
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
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o">Excel</i>',
                    titleAttr: 'Excel',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="fa fa-file-text-o">CSV</i>',
                    titleAttr: 'CSV',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o">PDF</i>',
                    titleAttr: 'PDF',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                }
            ],
        });
    })
</script>