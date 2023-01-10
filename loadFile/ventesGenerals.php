<?php
CommandeAchat::updateVueCmd();
$listcommandes = CommandeAchat::listGeneralCmd();

?>
<h3 style="text-align: center;">Liste des Commandes</h3>
<table class="table table-striped table-bordered table-hover" id="listcommandes">
    <thead>
        <tr class="th_color">
            <th rowspan="2" style="width: 2%;">#</th>
            <th rowspan="2" <?= $dt_filter_input ?>>Date Commande</th>
            <th rowspan="2" <?= $dt_filter_input ?>>Nom & Prenoms</th>
            <th rowspan="2" <?= $dt_filter_input ?>>telephone</th>
            <th rowspan="2" <?= $dt_filter_input ?>>Remise</th>
            <th rowspan="2" <?= $dt_filter_input ?>>Somme à payer</th>
            <th rowspan="2" <?= $dt_filter_input ?>>Type Paiement</th>
            <th rowspan="2" <?= $dt_filter_input ?>>Vendeurs</th>
            <th rowspan="2" <?= $dt_filter_input ?>>Prix Livraison</th>
            <th rowspan="2" <?= $dt_filter_input ?>>Etats</th>
            <th colspan="3" style="width: 8%;">Actions</th>
        </tr>
        <tr class="th_color">
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($listcommandes as $line) {

            if ($line['etatLivCom'] == 1)
                $etat = "En cours";
            else
                $etat = "Traitée";

            //	Beauty Fashion beauty@gmail.com  Vanessa Clear vanessa@gmail.com
            //$delete_style = $nb >= 1 && $line['ext_chap'] == 'pdf' ? "style=\"cursor: default; pointer-events: none; text-decoration: none; color: grey;\"" : "";
            //$view_file = $nb < 1 ? "style=\"cursor: default; pointer-events: none; text-decoration: none; color: grey;\"" : "";

        ?>
            <tr>
                <td></td>
                <td><?= GeneralClass::dateHeureFormatFr($line['DateCom']) ?></td>
                <td><?= $line['nomClt'] . "" . $line['prenomClt'] ?></td>
                <td><?= $line['telClt'] ?></td>
                <td><?= GeneralClass::prix_cfa($line['remCom']) ?></td>
                <td><?= GeneralClass::prix_cfa($line['somComPayer']) ?></td>
                <td><?= $line['libellePaiem'] ?></td>
                <td><?= CommandeAchat::listVendeurs($line['idCom']) ?></td>
                <td><?= GeneralClass::prix_cfa($line['prixLivCom']) ?></td>
                <td><?= $etat ?></td>
                <td><a class="viewfiles" href='#' idcom="<?= $line['idCom'] ?>" <?= $detail_icon_attr ?>><?= $TITLE_INFOS_DETAIL ?></a></td>
                <td><a class="action" href='form/confirmerPaiement.php?payer=true&idcommande=<?= $line['idCom'] ?>' <?= $affect_icon_attr ?>><?= $TITLE_CONFIRM_PAIEMENT ?></a></td>
                <td><a href="form/annulerCommandeVente.php?annuler=true&idcommande=<?= $line['idCom'] ?>" <?= $delete_icon_attr ?>><?= $TITLE_ANNULER ?></a></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<style>
</style>
<script type="text/javascript">
    $(document).ready(function() {

        function recharge_details_commandes(idcom) {
            $.mobile.loading('show');
            var ct = '<?= SITE_URL ?>loadFile/detailsCommandes.php';
            $('#list_commandes_details').empty().load(ct, {
                    idcom: idcom
                },
                function(html) {
                    $(this).html(html).trigger('create');
                    $.mobile.loading('hide');
                });
        }

        $(".viewfiles").on('click', function(e) {
            var idcom = $(this).attr("idcom");
            recharge_details_commandes(idcom)
            return false;
        });

        //Edit de compte
        commandetable = setupDataTables("listcommandes", {
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
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o">Excel</i>',
                    titleAttr: 'Excel',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="fa fa-file-text-o">CSV</i>',
                    titleAttr: 'CSV',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o">PDF</i>',
                    titleAttr: 'PDF',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                }
            ],
        });
    })
</script>