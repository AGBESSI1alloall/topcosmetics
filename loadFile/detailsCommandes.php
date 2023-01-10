<?php 
include_once '../loader.php';

$listdetaillee = CommandeAchat::detailComForCpt($idcom, $_SESSION['idCptVend']);

?>
<h3 style="text-align: center;">Détail de la commande</h3>
<table class="table table-striped table-bordered table-hover" id="listdetaillee">
    <thead>
        <tr class="th_orther_color">
            <th  style="width: 2%;">#</th>
            <th>Date Achat</th>
            <th>Nom Produit</th>
            <th>Prix Achat</th>
            <th>Quantité</th>
            <th>Somme Achat</th>
            <th>Vendeurs</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($listdetaillee as $line) {
            
        ?>
            <tr>
                <td></td>
                <td><?= GeneralClass::dateHeureFormatFr($line['dateAchat']) ?></td>
                <td><?= $line['nomProd'] ?></td>
                <td><?= GeneralClass::prix_cfa($line['prixAchat'])?></td>
                <td><?= $line['qteAchat'] ?></td>
                <td><?= GeneralClass::prix_cfa($line['SomTotAchat']) ?></td>
                <td><?= $line['nomCptVend'] ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<style>
</style>
<script type="text/javascript">
    $(document).ready(function() {

        commandedetaillee = setupDataTables("listdetaillee", {
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