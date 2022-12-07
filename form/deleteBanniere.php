<?php
require_once '../loader.php';

//Reponse à renvoyer au client sous forme JSON
$response = array('feedback' => "", 'response' => "");

if (isset($submit)) {

    if (!isset($id_ban) || empty($id_ban)) {

        $response['feedback'] = sms_error("Veuillez passer par le bon chemin, Merci!");
    } else {

        $line = Banniere::lineBanniere($id_ban);

        if (!empty($line['lienBanCpt'])) {
            $lien_file = "../{$line['lienBanCpt']}";
            if (is_file($lien_file))
                unlink($lien_file);
        }


        Banniere::deleteBanniere($id_ban);


        $response['feedback'] = 0;
        $response['response'] = $delete_success_msg;
    }
    print json_encode($response);
    exit();
}

?>

<div data-role="page" id="standard" data-add-back-btn="true">
    <div data-role="header">
        <h1>Supprimer cette Bannière</h1>
    </div>
    <div data-role="content">
        <form method="post" action="" id="deletebanniere" style="margin:10px 20px;">
            <div id="messageBox">

                <p style="color:red; text-align:center; border:1px solid red; border-radius:6px;margin:5px 0px;padding:8px 0px;">
                    <a href='#' data-role='button' data-icon='alert' data-iconpos='notext' data-inline='true'></a>
                    Voulez vous vraiment supprimer cet element?
                </p>
            </div>
            <input type="hidden" name="id_ban" id="id_ban" value="<?= $idban ?>" />

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
            var idm = '<?= $idmarque ?>';

            $(function() {
                $("#deletesubmit").on('click', function() {

                    params = $("#deletebanniere").serialize();
                    $('#messageBox').hide('slow');
                    AjaxLoader("deleteBanniere.php", params + '&submit=yes', $('#deletebanniere .messageBox'), '#content', 'banniere.php');
                    return false;
                });
            });
        </script>
    </div>
</div>