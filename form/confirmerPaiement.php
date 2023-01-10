<?php
require_once '../loader.php';

//Reponse à renvoyer au client sous forme JSON
$response = array('feedback' => "", 'response' => "");

if (isset($submit)) {    

    CommandeAchat::confirmPaiement($id_util, $id_commande);
    
    $response['feedback'] = 0;
    $response['response'] = sms_success("Le Paiement à été confirmé!");

    print json_encode($response);
    exit();
}
?>

<div data-role="page" id="standard" data-add-back-btn="true">
  <div data-role="header">
    <h1>Confirmer un paiement</h1>
  </div>
  <div data-role="content">
	<form method="post" action="" id="confirmpaiem" style="margin:10px 20px;">
            <div id="messageBox">
                
                <p style="color:red; text-align:center; border:1px solid red; border-radius:6px;margin:5px 0px;padding:8px 0px;">
                    <a href='#' data-role='button' data-icon='alert' data-iconpos='notext' data-inline='true'></a>
                    Voulez vous vraiment confirmer le paiement?
                </p>
            </div>
            <input type="hidden" name="id_commande" id="id_commande" value="<?= $idcommande ?>" />
            <div style="margin-bottom:10px"></div>

            <div class="messageBox" style="height: auto;"></div>
            <div class="twobutton" style="text-align:center">
                <input id="submit" name="submit" type="hidden" value="Enregistrer" data-mini="true" data-icon="edit" data-theme="b" />
                <a href="#" id="confirmsubmit" data-icon="delete" data-role="button" data-mini="true" data-icon="back" data-theme="b">Confirmer</a>
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
            var idproduit = "<?=$idproduit?>";
            $(function(){
                $("#confirmsubmit").on('click', function(){
                    params = $("#confirmpaiem").serialize();
                    $('#messageBox').hide('slow');
                    AjaxLoader("confirmerPaiement.php", params + '&submit=yes', $('#confirmpaiem .messageBox'), '#content','ventes.php');

                    return false;
                });
            });
        </script>
  </div>
</div>