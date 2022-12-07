<?php
include_once '../loader.php';

$listproduit = Produit::listProd($_SESSION['idCptVend']);

$idproduit = isset($idproduit) ? $idproduit : 0;
?>
<h3 style="text-align: center;">Liste des Produits</h3>
<table class="table table-striped table-bordered table-hover" id="listproduit">
    <thead>
        <tr class="th_color">
            <th rowspan="2" style="width: 2%;">#</th>
            <th rowspan="2" <?= $dt_filter_select ?>>Libelle</th>
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

            $nb_prod = Produit::nbImgProd($line['idProd']);
            //$delete_style = $nb >= 1 && $line['ext_chap'] == 'pdf' ? "style=\"cursor: default; pointer-events: none; text-decoration: none; color: grey;\"" : "";
            //$view_file = $nb < 1 ? "style=\"cursor: default; pointer-events: none; text-decoration: none; color: grey;\"" : "";

        ?>
            <tr>
                <td></td>
                <td><?= $line['nomProd'] ?></td>
                <td><?= $line['libCatVent'] ?></td>
                <td><?= nl2br($line['descProd'])?></td>
                <td><?= $line['prixProd'] ?></td>
                <td><?= $line['prixPromoProd'] ?></td>
                <td><?= $line['nbrePlace'] ?></td>
                <td><a class="viewfiles" href='#' idproduit="<?= $line['idProd'] ?>" <?= $detail_icon_attr ?>><?= $TITLE_INFOS_DETAIL ?></a></td>
                <td><a href='#' class="affectfile" idproduit="<?= $line['idProd'] ?>" nomproduit="<?= $line['nomProd'] ?>" <?= $affect_icon_attr ?>><?= titles("Affecter des Images") ?></a></td>
                <td><a class="editproduit" href='#' idproduit="<?= $line['idProd'] ?>" <?= $edit_icon_attr ?>><?= $TITLE_MODIF_INFOS ?></a></td>
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
            var idvoiture = "";
            var idmodele = "";

            if (!$("#contentajoutfile").hasClass('cache')) {
                $("#contentajoutfile").addClass('cache');
                idvoiture = "";
                var idmodele = "";
                $('#list_produit_img').empty();

            } else {
                $("#contentajoutfile").removeClass('cache');
                $("#contentajoutvoit").addClass('cache');
                idvoiture = $(this).attr("idvoiture");
                idmodele = $(this).attr("idmodele");
                recharge_file_produit(idvoiture);
            }
            console.log(idvoiture);
            $("#id_voiture_file").val(idvoiture).trigger('create');
            $("#libelle_voiture").val(idmodele).trigger('create');
        });

        //Gestion modification
        $(".editproduit").on('click', function(event) {
            var idvoiture = $(this).attr("idvoiture");
            $.ajax({
                type: 'POST',
                url: 'dynamicSelect.php',
                data: {
                    idvoiture: idvoiture
                },
                dataType: "json",
                success: function(response) {
                    var objet = response;
                    if (!$("#contentajoutvoit").hasClass('cache')) {

                    } else {
                        $("#contentajoutvoit").removeClass('cache');
                    }
                    $("#id_voiture").val(objet.idvoiture).trigger('create');
                    $("#annee_voit").val(objet.anneevoit).trigger('create');
                    $("#modele_voit").val(objet.modelevoit).trigger('create');
                    $("#chassis").val(objet.chassis).trigger('create');
                    $("#nbr_place").val(objet.nbrplace).trigger('create');
                    $("#origine_voit").val(objet.originevoit).trigger('create');
                    $("#type_moteur").val(objet.typemoteur).trigger('create');
                    $("#marque_voit").val(objet.marquevoit).trigger("chosen:updated");
                    //$("#id_prio").val(objet.idprio).selectmenu('refresh');
                    $("#id_prio").val(objet.idprio).trigger("chosen:updated");
                    $("#garantie_voit").val(objet.garantievoit).trigger('create');
                    $("#boite_vitesse").val(objet.boitevitesse).selectmenu('refresh');
                    var type = objet.type;
                    $("#old_type_action").val(type).trigger('create');
                    var elem = "#type_action_" + type;
                    $("[name='type_action']").prop("checked", false).checkboxradio('refresh');
                    $(elem).prop("checked", true).checkboxradio('refresh');
                    //$("input[type='radio']").change();

                    if (type == 1) {
                        $("#locatform1").removeClass('cache');
                        $("#locatform2").removeClass('cache');
                        $("#ventform").addClass('cache');

                    } else if (type == 2) {
                        $("#locatform1").addClass('cache');
                        $("#locatform2").addClass('cache');
                        $("#ventform").removeClass('cache');
                    } else if (type == 3) {
                        $("#ventform").removeClass('cache');
                        $("#locatform1").removeClass('cache');
                        $("#locatform2").removeClass('cache');
                    }

                    $("#prix_vente").val(objet.prixvente).trigger('create');
                    $("#garentie_vente").val(objet.garentievente).trigger('create');
                    $("#prix_locatj").val(objet.prixlocatj).trigger('create');
                    $("#prix_locath").val(objet.prixlocath).trigger('create');
                    $("#prix_locatm").val(objet.prixlocatm).trigger('create');
                    $("#prix_locatt").val(objet.prixlocatt).trigger('create');

                }
            });
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