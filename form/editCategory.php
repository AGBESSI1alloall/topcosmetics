<?php
include_once '../loader.php';


$response = array('feedback' => "", 'response' => "");

if (isset($submit)) {
    if (empty($lib_cat)) {

        $error = sms_error("Veuillez remplir tous les champs");

        $response['feedback'] = $error;

    } else {
        
        if (GeneralClass::checkDoublons($lib_cat, "categorie_vente", "libCatVent") && $lib_cat != $old_lib_cat) {

            $error = sms_error("Libelle Existe déja!!!");

            $response['feedback'] = $error;

        } else {

            Category::updateCategory($lib_cat, $desc_cat, $id_cat);

            $success = $save_success_msg;
            //Pas d'Erreur d'insertion, le script continue
            $response['feedback'] = 0;
            $response['response'] = $success;
        }
    }

    print json_encode($response);
    exit();
}

$donn_category = Category::lineCategory($idcatvent);

?>
<div data-role="page" id="standard" data-add-back-btn="true">
    <div data-role="header">
        <h1>Modifier une Catégorie</h1>
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
        <form method="post" action="" id="edit_cat" style="margin:10px 20px;">

            <div data-role='fieldcontain' class="formLine60">
                <input type="hidden" name="id_cat" id="id_cat" value="<?=$donn_category['idCatVent']?>"/>
                <input type="hidden" name="old_lib_cat" id="old_lib_cat" value="<?=$donn_category['libCatVent']?>"/>
                <label for='lib_cat'>Libelle :</label>
                <input type="text" name="lib_cat" id="lib_cat" maxlength="150" value="<?=$donn_category['libCatVent']?>" class='text ui-corner-tl' data-mini="true" /> 
            </div> 
            <div data-role='fieldcontain' class="formLine60">
                <label for='desc_cat'>Description :</label>
                <textarea name="desc_cat" id="desc_cat"><?=$donn_category['descCatVent']?></textarea> 
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
                    params = $("#edit_cat").serialize();
                    AjaxLoader("editCategory.php", params + '&submit=yes', $('#edit_cat .messageBox'), '#content', 'category.php');
                    //
                });
            });
        </script>
    </div>
</div>