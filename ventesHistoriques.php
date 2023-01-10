<?php
include_once 'loader.php';

$date = new DateTime();
$dateDeb = $date -> format('Y-m-01');
$dateFin = $date -> format('Y-m-t');


?>

<div id="all_content" style="width:98%; margin:auto;">
    <div id="content">
        <div id="contentsearchhistorestaurant" style="width: 80%; margin:10px auto; border: 1px solid grey; border-radius: 5px;">
            <div id="" style="margin:10px 0px; width: 100%;">
                <form method="post" name="search_histo_commandes" id="search_histo_commandes">
                    <table style="width:90%; margin: 10px auto;">
                        <tr>
                            <td style="width:20%;">
                                <div data-role='fieldcontain' class="formLine60 ui-field-contain">
                                    <label for='date_deb'>Debut:</label>
                                    <input type="text" name="date_deb" id="date_deb" value="<?= $dateDeb ?>" data-mini="true" />
                                </div>
                            </td>
                            <td style="width:20%;">
                                <div data-role='fieldcontain' class="formLine60 ui-field-contain">
                                    <label for='date_fin'>Fin:</label>
                                    <input type="text" name="date_fin" id="date_fin" value="<?= $dateFin ?>" data-mini="true" />
                                </div>
                            </td>
                            <td style="width:10%;">
                                <input type="hidden" name="submitsearch" value="submitsearch" />
                                <input type="submit" id="uploadSubmit" value="Rechercher" data-theme='b' data-mini='true' data-icon='check' />
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <div id="list_histo_commandes" style="width: 100%; margin-top: 20px;">

        </div>
        <div id="list_commandes_details" style="width: 98%; margin: 30px auto;">

        </div>
    </div>
</div>
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

    .griser {
        cursor: default;
        pointer-events: none;
        text-decoration: none;
        color: grey;
    }

    .formLine60 {
        margin-right: 5px;
    }

    .ui-select {
        top: 0px;
    }

    select {
        color: #000000;
    }

    .chosen-container.chosen-with-drop .chosen-drop {
        margin-left: 0px !important;
        width: 100%;
        left: 0px !important;
    }

    .chosen-single,
    .ui-link {
        width: 100%;
        height: 37px !important;
        margin-left: 0px;
    }

    .ui-field-contain label {
        width: 30% !important;
        margin: 0px !important;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {

        //Gestion des Dates
        $.datetimepicker.setLocale('fr');
        $('#search_histo_commandes #date_deb, #search_histo_commandes #date_fin').datetimepicker({
            lang: 'ru',
            format: 'Y-m-d',
            timepicker: false,
        });

        //Chargement du tableau des comptes

        $("#search_histo_commandes #uploadSubmit").on('click', function() {
            var datedeb = $("#date_deb").val();
            var datefin = $("#date_fin").val();

            var ct = '<?= SITE_URL ?>loadFile/ventesHistoriques.php';
            $('#list_histo_commandes').load(ct, {
                    datedeb: datedeb,
                    datefin: datefin
                },
                function(html) {
                    $(this).html(html).trigger('create');
                    historiccommandes
                    $.mobile.loading('hide');
                });

            return false;
        });

        $("#search_histo_commandes #uploadSubmit").click();
        

    });
</script>