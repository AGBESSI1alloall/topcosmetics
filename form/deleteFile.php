<?php
require_once '../loader.php';

//Reponse à renvoyer au client sous forme JSON
$response = array('feedback' => "", 'response' => "");

if (isset($submit)) {

    $file = ProduitImages::lineImgProd($id_doc);
    if (!empty($file['lienImgProd'])) {
        $lien_file = "../{$file['lienImgProd']}";
        if (is_file($lien_file))
            unlink($lien_file);
    }
    ProduitImages::deleteImgProd($id_doc);


    $response['feedback'] = 0;
    $response['response'] = $delete_success_msg;

    print json_encode($response);
    exit();
}
?>

<div data-role="page" id="standard" data-add-back-btn="true">
    <div data-role="header">
        <h1>Supprimer un Fichier</h1>
    </div>
    <div data-role="content">
        <form method="post" action="" id="deletefile" style="margin:10px 20px;">
            <div id="messageBox">

                <p style="color:red; text-align:center; border:1px solid red; border-radius:6px;margin:5px 0px;padding:8px 0px;">
                    <a href='#' data-role='button' data-icon='alert' data-iconpos='notext' data-inline='true'></a>
                    Voulez vous vraiment supprimer cet element?
                </p>
            </div>
            <input type="hidden" name="id_prod" id="id_prod" value="<?=$idproduit?>" />
            <input type="hidden" name="id_doc" id="id_doc" value="<?=$iddoc?>" />

            <div style="margin-bottom:10px"></div>

            <div class="messageBox" style="height: auto;"></div>
            <div class="twobutton" style="text-align:center">
                <input id="submit" name="submit" type="hidden" value="Enregistrer" data-mini="true" data-icon="edit" data-theme="b" />
                <a href="#" id="deletesubmit" data-icon="delete" data-role="button" data-mini="true" data-icon="back" data-theme="b">Supprimer</a>
                <a href="#" id="cancel" data-rel="back" data-role="button" data-mini="true" data-icon="back" data-theme="a">Annuler</a>
            </div>
            <div class="formLine onebutton" style="display:none; text-align:center">
                <a href="#" data-rel="back" id="close" data-role="button" data-mini="true" data-icon="check" data-theme="b">Ok</a>
            </div>
            <div class="formLine onebuttonerror" style="display:none; text-align:center">
                <a href="#" data-rel="back" data-role="button" data-mini="true" data-icon="back" data-theme="a">Fermer</a>
            </div>
        </form>
        <script type="text/javascript">
            var idp = '<?= $idproduit ?>';
            
            $(function () {
                $("#deletesubmit").on('click', function () {

                    params = $("#deletefile").serialize();
                    $('#messageBox').hide('slow');
                    AjaxLoader("deleteFile.php", params + '&submit=yes', $('#deletefile .messageBox'), '#all_details','produits.php?idproduit='+idp);
                    /*AjaxLoader("deleteFile.php", params + '&submit=yes', $('#deletefile .messageBox'), function () {
                        $("#all_details").empty().load(
                                '<?=SITE_URL?>produits.php',
                                {
                                    idproduit: idp
                                },
                                function () {
                                    $(this).trigger('create');
                                });
                    });*/

                    return false;
                });
            });
        </script>
    </div>
</div>