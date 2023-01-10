<?php
include_once 'loader.php';

?>

<div id="all_details" style="width:98%; margin:auto;">
    <div id="content">
        
        <div id="list_commandes" style="width: 100%; margin-top: 20px;">

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
</style>
<script type="text/javascript">
    var idcmd = "<?= $idcmd ?>";

    $(document).ready(function() {

        //Chargement du tableau des comptes
        recharge_table_commandes();

        function recharge_table_commandes() {
            $.mobile.loading('show');
            var ct = '<?= SITE_URL ?>loadFile/ventes.php';
            $('#list_commandes').load(ct, {},
                function(html) {
                    $(this).html(html).trigger('create');
                    commandetable
                    $.mobile.loading('hide');
                });
        }

        setInterval(() => {
            var ct = '<?= SITE_URL ?>loadFile/ventes.php';
            $('#list_commandes').load(ct, {},
                function(html) {
                    $(this).html(html).trigger('create');
                    commandetable
                });
            return false;
        }, 60000)

    });
</script>