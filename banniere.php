<?php
include_once 'loader.php';

//$style_ban = count($liste_banniere) ? "opacity: .5;" : "";
$response = ['feedback' => "", 'response' => ""];


if (isset($submitbanfile)) {

    $liste_banniere = Banniere::listBanniere($_SESSION['idCptVend']);

    if(count($liste_banniere) && isset($id_ban) && empty($id_ban)){
        $response['feedback'] = sms_error("Vous avez déjà une Bannière!");
    }else
    if (empty($_FILES['uploadBanniereFile']['name'])) {

        $response['feedback'] = sms_error("Veuillez selectionner un fichier!");

    } else {

        if (is_uploaded_file($_FILES['uploadBanniereFile']['tmp_name'])) {
            sleep(1);

            $date_du_jour = date("YmdHis");
            $name = $date_du_jour . '_' . $_FILES['uploadBanniereFile']['name'];

            $mon_dossier1 = "images/compte_bannieres";
            $mon_dossier2 = "images/compte_bannieres";

            if (!file_exists($mon_dossier1)) {
                mkdir($mon_dossier1, 0777, true);
            }

            $source_path = $_FILES['uploadBanniereFile']['tmp_name'];

            $target_path = $mon_dossier1 . '/' . $name;
            $doc_file = $mon_dossier2 . '/' . $name; //substr($target_path, 3);
            $doc_name = $name;
            $infosfichier = pathinfo($_FILES['uploadBanniereFile']['name']);
            $doc_ext = $infosfichier['extension'];

            $ext = $doc_ext;
            $file = $doc_name;
            $lien = $doc_file;
            //Upload images
            if (move_uploaded_file($source_path, $target_path)) {

                if (isset($id_ban) && !empty($id_ban)) {

                    if (!empty($lien_ban)) {
                        $lien_file = "$lien_ban";
                        if (is_file($lien_file))
                            unlink($lien_file);
                    }
                    
                    Banniere::updateBanniere($file, $lien, $ext, $id_ban);

                } else {

                    Banniere::insertBanniere($_SESSION['idCptVend'], $file, $lien, $ext);

                }
            }

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
<div id="all" style="width:98%; margin:20px auto;">
    <div id="content">
        <table style="width:100%;">
            <tr>
                <td style="width:40%; vertical-align: top;">
                    <h3 class="addtitle" style="text-align: center;">Ajouter une Banniere</h3>
                    <form method="post" name="addbanniere" id="addbanniere" enctype="multipart/form-data" style="width:80%; margin: 30px auto;">
                        <p class="lastimage">

                        </p>
                        <div data-role='fieldcontain' class="formLine60 readonly">
                            <input type="hidden" name="id_ban" id="id_ban" data-mini="true" value="" />
                            <input type="hidden" name="lien_ban" id="lien_ban" data-mini="true" value="" />
                            <label for="uploadBanniereFile" data-mini='true' class="logo">Photo Bannière : </label>
                            <input type="file" name="uploadBanniereFile" id="uploadBanniereFile" accept=".jpg, .png, .jpeg" />
                        </div>
                        <div id="progress-wrp" class="cache">
                            <div class="progress-bar"></div>
                            <div class="status">0%</div>
                        </div>
                        <div style="margin: auto;">
                            <div class="messageBox" style="height: auto;"></div>
                            <div class="separator" style="margin-top : 30px"></div>
                            <div id="ajaxloader" style="margin:8px auto"></div>
                            <div id="actual" style="display:none;"><input type="reset" value="Reset" id="actualiseForm"></div>
                            <input type="hidden" name="submitbanfile" value="submitbanfile" />
                            <input type="submit" id="uploadSubmit" value="Enregistrer" data-theme='b' data-mini='true' data-icon='check' />
                            <div id="targetLayer" style="display:none;"></div>
                        </div>
                    </form>
                </td>
                <td style="width:58%; vertical-align: top;">
                    <div id="list_banniere" style="width: 100%; margin-top: 20px;"></div>
                </td>
            </tr>
        </table>
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

    #progress-wrp {
        border: 1px solid #0099CC;
        padding: 1px;
        position: relative;
        border-radius: 3px;
        margin: 10px;
        text-align: left;
        background: #fff;
        box-shadow: inset 1px 3px 6px rgba(0, 0, 0, 0.12);
        height: 20px;
    }

    #progress-wrp .progress-bar {
        height: 18px;
        border-radius: 3px;
        background-color: #32CD32;
        width: 0;
        box-shadow: inset 1px 1px 10px rgba(0, 0, 0, 0.11);
    }

    #progress-wrp .status {
        top: 0px;
        left: 50%;
        position: absolute;
        display: inline-block;
        color: #000000;
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {

        //validation des images du voiture
        $('#addbanniere').submit(function(event) {
            $("#progress-wrp").removeClass('cache');
            $.mobile.loading('show');
            var fData = new FormData($("#addbanniere")[0]);
            $.ajax({
                type: "POST",
                url: 'banniere.php',
                data: fData,
                processData: false,
                contentType: false,
                cache: false,
                dataType: "json",
                beforeSend: function() {
                    $('#uploadSubmit').attr("disabled", "disabled");
                    $('#addbanniere').css("opacity", ".5");
                },
                xhr: function() {
                    //upload Progress
                    var xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', function(event) {
                            var percent = 0;
                            var position = event.loaded || event.position;
                            var total = event.total;
                            if (event.lengthComputable) {
                                percent = Math.ceil(position / total * 100);
                            }
                            //update progressbar
                            $("#progress-wrp" + " .progress-bar").css("width", +percent + "%");
                            $("#progress-wrp" + " .status").text(percent + "%");
                        }, true);
                    }
                    return xhr;
                },
                mimeType: "multipart/form-data",
                success: function(response) {
                    var objet = response;
                    var percent = 0;
                    if (objet.feedback === 0) {
                        $("#addbanniere .messageBox").html(objet.response).trigger('create');
                        setTimeout(function() {
                            $.mobile.loading("hide");
                            recharge_table_banniere();
                            $('#addbanniere .messageBox').html("").trigger("create");
                            //$("#createcompte #id_client").html("").trigger("chosen:updated");
                            $("#addbanniere #actualiseForm").click();
                            $("#addbanniere #uploadBanniereFile").val("").trigger("create");
                            $('#addbanniere').css("opacity", "");
                            $("#progress-wrp" + " .progress-bar").css("width", +percent + "%");
                            $("#progress-wrp" + " .status").text(percent + "%");
                            $("#progress-wrp").addClass('cache');
                            $("#uploadSubmit").removeAttr("disabled");
                        }, 4000);
                        $("#id_ban").val("").trigger('create');
                        $("#lien_ban").val("").trigger('create');
                        $(".lastimage").html("").trigger('create');
                        $(".addtitle").html("Ajouter une Banniere").trigger('create');
                    } else {
                        $("#addbanniere .messageBox").html(objet.feedback).trigger('create');
                        $('#addbanniere').css("opacity", "");
                        $("#progress-wrp" + " .progress-bar").css("width", +percent + "%");
                        $("#progress-wrp" + " .status").text(percent + "%");
                        $("#progress-wrp").addClass('cache');
                        $("#uploadSubmit").removeAttr("disabled");
                        $.mobile.loading("hide");
                    }
                    $("#addbanniere")[0].reset(); //reset form
                    //$(result_output).html(objet); //output response from server
                    //submit_btn.val("Upload").prop("disabled", false);
                }
            });
            return false;
        });

        //Chargement du tableau des comptes
        recharge_table_banniere();

        function recharge_table_banniere() {
            $.mobile.loading('show');
            var ct = '<?= SITE_URL ?>loadFile/banniere.php';
            $('#list_banniere').load(ct, {},
                function(html) {
                    $(this).html(html).trigger('create');
                    bannieretable
                    $.mobile.loading('hide');
                });
        }

    });
</script>