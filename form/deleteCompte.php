<?php
require_once '../loader.php';

if(isset($desactive) && $desactive){
    $title = "Désactivé un compte";
    $alert = "Voulez vous vraiment désactiver ce Compte?";
    $btn = "Désactivé";
    $type = 0;
}else{
    $title = "Activé un compte";
    $alert = "Voulez vous vraiment activer ce Compte?";
    $btn = "Activé";
    $type = 1;
}
    

//Reponse à renvoyer au client sous forme JSON
$response = array('feedback' => "", 'response' => "");

if (isset($submit)) {    

    if(isset($type_cpt_vend) && $type_cpt_vend == 0)
        Compte::desactiveCompte($id_cpt_vend);
    else
        Compte::activerCompte($id_cpt_vend);
    
    $response['feedback'] = 0;
    $response['response'] = sms_success("L'action a été bien effectuée!");

    print json_encode($response);
    exit();
}
?>

<div data-role="page" id="standard" data-add-back-btn="true">
  <div data-role="header">
    <h1><?=$title?></h1>
  </div>
  <div data-role="content">
	<form method="post" action="" id="deletecompte" style="margin:10px 20px;">
            <div id="messageBox">
                
                <p style="color:red; text-align:center; border:1px solid red; border-radius:6px;margin:5px 0px;padding:8px 0px;">
                    <a href='#' data-role='button' data-icon='alert' data-iconpos='notext' data-inline='true'></a>
                    <?= $alert ?>
                </p>
            </div>
            <input type="hidden" name="id_cpt_vend" id="id_cpt_vend" value="<?= $idcptvend ?>"  />
            <input type="hidden" name="type_cpt_vend" id="type_cpt_vend" value="<?= $type ?>"  />
            <div style="margin-bottom:10px"></div>

            <div class="messageBox" style="height: auto;"></div>
            <div class="twobutton" style="text-align:center">
                <input id="submit" name="submit" type="hidden" value="Enregistrer" data-mini="true" data-icon="edit" data-theme="b" />
                <a href="#" id="deletesubmit" data-icon="delete" data-role="button" data-mini="true" data-icon="back" data-theme="b"><?=$btn?></a>
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
            $(function(){
                $("#deletesubmit").on('click', function(){
                    
                    params = $("#deletecompte").serialize();
                    $('#messageBox').hide('slow');
                    AjaxLoader("deleteCompte.php", params + '&submit=yes', $('#deletecompte .messageBox'), '#content','compte.php');
                    
                    return false;
                });
            });
        </script>
  </div>
</div>