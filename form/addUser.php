<?php
include_once '../loader.php';
//
$response = array('feedback' => "", 'response' => "");

if (isset($submit)) {
    if (empty($nom_user) || empty($prenom_user) || empty($tel_user) || empty($email_user) || empty($pwd_user)) {

        $error = sms_error("Veuillez remplir tous les champs");

        $response['feedback'] = $error;
    } else {
        
        if (GeneralClass::checkDoublons($email_user, "user", "emailUser")) {

            $error = sms_error("Email Existe déja!!!");

            $response['feedback'] = $error;
        } else {

            $id_user = User::insertUser($_SESSION['idCptVend'],$nom_user, $prenom_user, $tel_user, $email_user, $pwd_user,'utilisateur');
            Menu::insertMenuUser($id_user,3,3);

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
        <h1>Ajouter un Utilisateur</h1>
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
        <form method="post" action="" id="add_user" style="margin:10px 20px;">

            <div data-role='fieldcontain' class="formLine60">
                <label for='nom_user'>Nom :</label>
                <input type="text" name="nom_user" id="nom_user" maxlength="150" value="" class='text ui-corner-tl' data-mini="true" /> 
            </div> 
            <div data-role='fieldcontain' class="formLine60">
                <label for='prenom_user'>Prenoms :</label>
                <input type="text" name="prenom_user" id="prenom_user" maxlength="150" value="" class='text ui-corner-tl' data-mini="true" /> 
            </div> 
            <div data-role='fieldcontain' class="formLine60">
                <label for='tel_user'>Tel :</label>
                <input type="text" name="tel_user" id="tel_user" maxlength="150" value="" class='text ui-corner-tl' data-mini="true" required /> 
            </div> 
            <div data-role='fieldcontain' class="formLine60">
                <label for='email_user'>Email :</label>
                <input type="text" name="email_user" id="email_user" maxlength="150" value="" class='text ui-corner-tl' data-mini="true" required /> 
            </div>
            <div data-role='fieldcontain' class="formLine60">
                <label for='pwd_user'>Mot de passe :</label>
                <input type="text" name="pwd_user" id="pwd_user" maxlength="150" value="" class='text ui-corner-tl' data-mini="true" required /> 
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
                    params = $("#add_user").serialize();
                    AjaxLoader("addUser.php", params + '&submit=yes', $('#add_user .messageBox'), '#content', 'users.php');
                    //
                });
            });
        </script>
    </div>
</div>

