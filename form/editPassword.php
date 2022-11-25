<?php
include_once '../loader.php';

if(isset($general) && $general)
    $file =1;
else 
    $file =0;
//
$response = array('feedback' => "", 'response' => "");

if (isset($submit)) {

    if (empty($pwd_user)) {

        $error = sms_error("Veuillez remplir le champ");

        $response['feedback'] = $error;
    } else {


        if(!User::verifyOldPassword($old_pwd_user, $id_user)){
            $response['feedback'] = sms_error("L'ancien mot de passe n'est pas conforme");
        }else{

            User::changePasswordUser($pwd_user, $id_user);
            $success = $save_success_msg;
            //Pas d'Erreur d'insertion, le script continue
            $response['feedback'] = 0;
            $response['response'] = $success;
        }

        
    }

    print json_encode($response);
    exit();
}


?>
<div data-role="page" id="standard" data-add-back-btn="true">
    <div data-role="header">
        <h1>Modifier Mot de passe</h1>
    </div>
    <style>
        .ui-dialog-contain {
            width: 100%;
            max-width: 500px;
            padding: 0;
        }

        select {
            color: #000000;
        }
    </style>
    <div data-role="content">
        <form method="post" action="" id="edit_password" style="margin:10px 20px;">
            <div data-role='fieldcontain' class="formLine60">
                <label for='old_pwd_user'>Ancien mot de passe :</label>
                <input type="text" name="old_pwd_user" id="old_pwd_user" maxlength="256" value="" class='text ui-corner-tl' data-mini="true" required />
            </div>
            <div data-role='fieldcontain' class="formLine60">
                <input type="hidden" name="id_user" value="<?= $iduser ?>" />
                <label for='pwd_user'>Mot de passe :</label>
                <input type="text" name="pwd_user" id="pwd_user" maxlength="256" value="" class='text ui-corner-tl' data-mini="true" required />
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
            var file = "<?=$file?>";
            $(function() {
                //Creation de l'employé
                $("#butsubmit").on('click', function() {
                    if(file == "1")
                        lien = 'users.php';
                    else
                        lien = 'user.php';

                    params = $("#edit_password").serialize();
                    AjaxLoader("editPassword.php", params + '&submit=yes', $('#edit_password .messageBox'), '#content', lien);
                    //
                });
            });
        </script>
    </div>
</div>