<?php
include_once '../loader.php';

$response = array('feedback' => "", 'response' => "");

if (isset($submit)) {
    if (empty($nom_cpt_vend) || empty($adres_cpt_vend) || empty($tel_cpt_vend) || empty($email_cpt_vend)) {

        $error = sms_error("Veuillez remplir tous les champs");

        $response['feedback'] = $error;
    } else {
        
        if (GeneralClass::checkDoublons($email_cpt_vend, "compte_vendeur", "emailCptVend") && $email_cpt_vend != $old_email_cpt_vend) {

            $error = sms_error("Email Existe déja!!!");

            $response['feedback'] = $error;

        } else {

            Compte::updateCompte($nom_cpt_vend, $slogan_cpt_vend, $tel_cpt_vend, $email_cpt_vend, $adres_cpt_vend, $desc_cpt_vend, $id_cpt_vend);

            $success = $save_success_msg;
            //Pas d'Erreur d'insertion, le script continue
            $response['feedback'] = 0;
            $response['response'] = $success;
        }
    }

    print json_encode($response);
    exit();
}

$donn_Compte = Compte::lineOfCompte($idcptvend);
?>
<div data-role="page" id="standard" data-add-back-btn="true">
    <div data-role="header">
        <h1>Modifier le Compte</h1>
    </div>
    <style>
        .ui-dialog-contain {
            width: 100%;
            max-width: 500px;
            padding: 0;
        }
        select{color: #000000;}
    </style>
    <div data-role="content">
        <form method="post" action="" id="edit_compte" style="margin:10px 20px;">

            <div data-role='fieldcontain' class="formLine60">
                <input type="hidden" name="id_cpt_vend" id="id_cpt_vend" value="<?=$idcptvend?>"/>
                <label for='nom_cpt_vend'>Nom :</label>
                <input type="text" name="nom_cpt_vend" id="nom_cpt_vend" maxlength="150" value="<?=$donn_Compte[0]['nomCptVend']?>" class='text ui-corner-tl' data-mini="true" /> 
            </div> 
            <div data-role='fieldcontain' class="formLine60">
                <label for='slogan_cpt_vend'>Slogan :</label>
                <input type="text" name="slogan_cpt_vend" id="slogan_cpt_vend" maxlength="150" value="<?=$donn_Compte[0]['sloganCptVend']?>" class='text ui-corner-tl' data-mini="true" /> 
            </div> 
            <div data-role='fieldcontain' class="formLine60">
                <label for='tel_cpt_vend'>Tel :</label>
                <input type="text" name="tel_cpt_vend" id="tel_cpt_vend" maxlength="150" value="<?=$donn_Compte[0]['telCptVend']?>" class='text ui-corner-tl' data-mini="true" required /> 
            </div> 
            <div data-role='fieldcontain' class="formLine60">
                <label for='email_cpt_vend'>Email :</label>
                <input type="hidden" name="old_email_cpt_vend" id="old_email_cpt_vend" value="<?=$donn_Compte[0]['emailCptVend']?>"/>
                <input type="text" name="email_cpt_vend" id="email_cpt_vend" maxlength="150" value="<?=$donn_Compte[0]['emailCptVend']?>" class='text ui-corner-tl' data-mini="true" required /> 
            </div>
            <div data-role='fieldcontain' class="formLine60">
                <label for='adres_cpt_vend'>Adresse :</label>
                <input type="text" name="adres_cpt_vend" id="adres_cpt_vend" maxlength="150" value="<?=$donn_Compte[0]['adresCptVend']?>" class='text ui-corner-tl' data-mini="true" /> 
            </div>
            <div data-role='fieldcontain' class="formLine60">
                <label for='desc_cpt_vend'>Descript.. :</label>
                <textarea name="desc_cpt_vend" id="desc_cpt_vend" cols="30" rows="10"><?= nl2br($donn_Compte[0]['descCptVend'])?></textarea> 
            </div> 
            <div style="margin-bottom:10px"></div>
            <div class="messageBox" style="height: auto;"></div>
            <div class="twobutton" style="text-align:center">
                <input id="submit" name="submit" type="hidden" value="Enregistrer" data-mini="true" data-icon="edit" data-theme="b" />
                <a href="#" id="butsubmit" data-icon="edit" data-role="button" data-mini="true" data-icon="back" data-theme="b">Enregistrer</a>
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

            $(function () {
                //Creation de l'employé
                $("#butsubmit").on('click', function () {
                    params = $("#edit_compte").serialize();
                    AjaxLoader("editCompte.php", params + '&submit=yes', $('#edit_compte .messageBox'), '#content', 'compte.php');
                    //
                });
            });
        </script>
    </div>
</div>