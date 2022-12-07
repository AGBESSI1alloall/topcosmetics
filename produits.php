<?php
include_once 'loader.php';


$list_categorie = "";
$cat = Category::listCategory();
$list_categorie = "<option value=\"\">Selectionner</option>";
foreach ($cat as $line) {
    $list_categorie .= "<option value=\"{$line['idCatVent']}\">{$line['libCatVent']}</option>";
}

$idproduit = isset($idproduit) ? $idproduit : 0;

$today = date("Y-m-d");

$response = ['feedback' => "", 'response' => "", 'idvoiture' => ""];

if (isset($submitvoitfile)) {

    if (empty($id_prod_file)) {

        $error = sms_error("Veuillez bien uploader le fichier");
        $response['feedback'] = $error;
    } else {

        $filesArr = $_FILES["uploadVoitFile"];

        $fileNames = array_filter($filesArr['name']);

        $date_du_jour = date("YmdHis");

        if (empty($fileNames)) {

            $response['feedback'] = sms_error("Veuillez selectionner au moins un fichier!");

        } else {

            $mon_dossier1 = "../MobileEzRentalCars/images/Voitures";
            $mon_dossier2 = "images/Voitures";


            if (!file_exists($mon_dossier1)) {
                mkdir($mon_dossier1, 0777, true);
            }

            foreach ($filesArr['name'] as $key => $val) {

                $name = $date_du_jour . '_' . basename($filesArr['name'][$key]);

                $source_path = $filesArr["tmp_name"][$key];

                $target_path = $mon_dossier1 . '/' . $name;
                $doc_file = $mon_dossier2 . '/' . $name; //substr($target_path, 3);
                $doc_name = $name;

                $fileType = pathinfo($target_path, PATHINFO_EXTENSION);

                $ext = $fileType;
                $file = $doc_name;
                $lien = $doc_file;
                //Upload images
                if (move_uploaded_file($source_path, $target_path)) {

                    PhotoVoiture::photoVoitureInsert($id_prod_file, $lien, $ext, $id_util);
                }
            }

            $success = $save_success_msg;
            //Pas d'Erreur d'insertion, le script continue
            $response['feedback'] = 0;
            $response['idvoiture'] = $id_prod_file;
            $response['response'] = $success;
        }
    }

    print json_encode($response);
    exit();
}

if (isset($submitproduit)) {

    if (empty($type_action) || empty($cat_prod) || empty($modele_voit) || empty($boite_vitesse)) {

        $error = sms_error("Veuillez remplir les Champss");

        $response['feedback'] = $error;
    } else if ($type_action == 2 && !Vehicle::nbAnneeValable($annee_voit)) {
        $error = sms_error("Svp, le nombre d'Année de la voiture est de trop!");

        $response['feedback'] = $error;
    } else {

        if (isset($id_prod) && !empty($id_prod)) {

            Vehicle::voitureUpdate($cat_prod, $id_prio, $annee_voit, $modele_voit, $chassis, $nbr_place, $origine_voit, $type_moteur, $type_action, $id_prod, $garantie_voit, $boite_vitesse);

            if ($type_action == 1) {

                if (Vlocation::checkIdVoiLocation($id_prod))
                    Vlocation::vLocationUpdate($prix_locatj, $prix_locath, $prix_locatm, $prix_locatt, $id_prod);
                else
                    Vlocation::vLocationInsert($id_prod, $prix_locatj, $prix_locath, $prix_locatm, $prix_locatt);
            } else if ($type_action == 2) {

                if (Vvente::checkIdVoiVente($id_prod))
                    Vvente::vVenteUpdate($prix_vente, $garentie_vente, $id_prod);
                else
                    Vvente::vVenteInsert($id_prod, $prix_vente, $garentie_vente);
            } else {
                if (Vlocation::checkIdVoiLocation($id_prod))
                    Vlocation::vLocationUpdate($prix_locatj, $prix_locath, $prix_locatm, $prix_locatt, $id_prod);
                else
                    Vlocation::vLocationInsert($id_prod, $prix_locatj, $prix_locath, $prix_locatm, $prix_locatt);

                if (Vvente::checkIdVoiVente($id_prod))
                    Vvente::vVenteUpdate($prix_vente, $garentie_vente, $id_prod);
                else
                    Vvente::vVenteInsert($id_prod, $prix_vente, $garentie_vente);
            }
        } else {
            $idvoit = Vehicle::voitureInsert($cat_prod, $id_prio, $annee_voit, $modele_voit, $chassis, $nbr_place, $origine_voit, $type_moteur, $type_action, $garantie_voit, $boite_vitesse);

            if ($type_action == 1) {
                Vlocation::vLocationInsert($idvoit, $prix_locatj, $prix_locath, $prix_locatm, $prix_locatt);
            } else if ($type_action == 2) {
                Vvente::vVenteInsert($idvoit, $prix_vente, $garentie_vente);
            } else {
                Vlocation::vLocationInsert($idvoit, $prix_locatj, $prix_locath, $prix_locatm, $prix_locatt);
                Vvente::vVenteInsert($idvoit, $prix_vente, $garentie_vente);
            }
        }

        $success = $save_success_msg;
        //Pas d'Erreur d'insertion, le script continue
        $response['feedback'] = 0;
        $response['response'] = $success;
    }

    print json_encode($response);
    exit();
}
?>

<div id="all_details" style="width:98%; margin:auto;">
    <div id="content">
        <div style='text-align:center; margin-top:5px;'>
            <a href="#" data-role='button' data-mini='true' id="ajout_produit" data-icon='plus' data-inline='true' data-theme='b'>Ajout Produits</a>
        </div>
        <table style="width:100%;">
            <tr>
                <td style="width:55%; vertical-align: top;">
                    <div id="contentajoutprod" class="cache" style="width: 100%; border: 1px solid grey; border-radius: 5px;">
                        <div id="" style="margin:10px 0px; width: 100%;">
                            <?php include_once 'form/vehicles.php'; ?>
                        </div>
                    </div>
                </td>
                <td style="width:44%; vertical-align: top;">
                    <div id="contentajoutfile" class="cache" style="width: 100%; border: 1px solid grey; border-radius: 5px;">
                        <div id="" style="margin:10px 0px; width: 100%;">
                            <?php include_once  'form/vehicleFiles.php'; ?>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <div id="list_produit" style="width: 100%; margin-top: 20px;">

        </div>
        <div id="list_produit_img" style="width: 100%; margin-top: 20px;">

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
    var idproduit = "<?= $idproduit ?>";

    $(document).ready(function() {

        //chosen sur le select
        $("#id_prio, #cat_prod").chosen();
        //Autocompletede
        $("#annee_voit").autocomplete({
            lookup: <?= json_encode($elem_annee) ?>
        });
        $("#modele_voit").autocomplete({
            lookup: <?= json_encode($elem_modele) ?>
        });
        $("#origine_voit").autocomplete({
            lookup: <?= json_encode($elem_origine) ?>
        });
        $("#type_moteur").autocomplete({
            lookup: <?= json_encode($elem_type_moteur) ?>
        });
        //Ouverture du formulaire
        $("#ajout_produit").click(function() {
            if (!$("#contentajoutprod").hasClass('cache')) {
                $("#contentajoutprod").addClass('cache');
            } else {
                $("#contentajoutprod").removeClass('cache');
                $("#contentajoutfile").addClass('cache');
                $('#list_produit_img').empty();
            }
        });

        //Gestion de radio
        $("input[type='radio']").change();
        $("input[type='radio']").on("change", function(event, ui) {
            var donn = $(this).val();
            if (typeof donn == "undefined") {
                $("#ventform").addClass('cache');
                $("#locatform1").addClass('cache');
                $("#locatform2").addClass('cache');
            } else
            if (donn == 1) {
                $("#locatform1").removeClass('cache');
                $("#locatform2").removeClass('cache');
                $("#ventform").addClass('cache');

            } else if (donn == 2) {
                $("#locatform1").addClass('cache');
                $("#locatform2").addClass('cache');
                $("#ventform").removeClass('cache');
            } else if (donn == 3) {
                $("#ventform").removeClass('cache');
                $("#locatform1").removeClass('cache');
                $("#locatform2").removeClass('cache');
            }
        });

        /*$("input[name='radio_choice_v_6']").on("change", function() {
            if ($("input[name='radio_choice_v_6']:checked").val() == 'numero')
                $("#labelAutreNumero").show();
            else
                $("#labelAutreNumero").hide();
        });*/
        //validation des images du voiture
        $('#addprodfile').submit(function(event) {
            //var fData = new FormData($("#addprodfile")[0]);
            $.ajax({
                type: "POST",
                url: 'vehicules.php',
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                dataType: "json",
                beforeSend: function() {
                    //$("#progress-wrp").removeClass('cache');
                    $('#uploadSubmit').attr("disabled", "disabled");
                    $('#addprodfile').css("opacity", ".5");
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
                    if (objet.feedback === 0) {
                        $("#addprodfile .messageBox").html(objet.response).trigger('create');
                        setTimeout(function() {
                            $.mobile.loading("hide");
                            recharge_table_produit();
                            $('#addprodfile .messageBox').html("").trigger("create");
                            //$("#createcompte #id_client").html("").trigger("chosen:updated");
                            $("#addprodfile #uploadVoitFile").val("").trigger("create");
                            recharge_file_produit(objet.idvoiture);
                            $('#addprodfile').css("opacity", "");
                            $("#progress-wrp" + " .progress-bar").css("width", +percent + "%");
                            $("#progress-wrp" + " .status").text(percent + "%");
                            //$("#progress-wrp").addClass('cache');
                            $("#uploadSubmit").removeAttr("disabled");

                        }, 4000);
                    } else {
                        $("#addprodfile .messageBox").html(objet.feedback).trigger('create');
                        $('#addprodfile').css("opacity", "");
                        $("#progress-wrp" + " .progress-bar").css("width", +percent + "%");
                        $("#progress-wrp" + " .status").text(percent + "%");
                        //$("#progress-wrp").addClass('cache');
                        $("#uploadSubmit").removeAttr("disabled");
                    }
                }
            });
            return false;
        });

        //Verifier la quantité de fichier prisent
        var match = ['image/jpeg', 'image/png', 'image/jpg'];
        $("#uploadVoitFile").change(function() {
            //var ext_file = $("#ext_chap_file").val();
            if (this.files.length > 8) {
                alert('Vous ne pouvez pas selectionner plus de 08 images');
                $("#uploadVoitFile").val('');
                return false;
            }else{
                for (i = 0; i < this.files.length; i++) {
                    var file = this.files[i];
                    var fileType = file.type;
                    if (!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]))) {
                        alert('Svp, Veuillez selectionner que des fichiers JPG, JPEG et PNG pour envoyer.');
                        $("#uploadVoitFile").val('');
                        return false;
                    }
                }
            }
        });

        //Chargement des files
        $('#addveh').submit(function(event) {
            $('#addveh').css("opacity", ".5");
            $('#uploadSubmit').attr("disabled", "disabled");
            params = $("#addveh").serialize();
            //$('#addveh').css("opacity", "");
            //$("#uploadSubmit").removeAttr("disabled");
            AjaxLoader("vehicules.php", params + '&submit=yes', $('#addveh .messageBox'), function() {
                setTimeout(function() {
                    $.mobile.loading("hide");
                    recharge_table_produit();
                    $('#addveh .messageBox').html("").trigger("create");
                    $('#addveh').css("opacity", "");
                    $("#uploadSubmit").removeAttr("disabled");
                    //$("#createcompte #id_client").html("").trigger("chosen:updated");
                    $("#addveh #actualiseForm").click();
                    console.log("ok valider");
                    if ($("#contentajoutprod").hasClass('cache')) {

                    } else {
                        $("#contentajoutprod").addClass('cache');
                    }
                }, 4000);
                //return false;
            });
            setTimeout(function() {
                $('#addveh').css("opacity", "");
                $("#uploadSubmit").removeAttr("disabled");
            }, 4000);
            console.log("ok fin");
            return false;
        });

        //Chargement du tableau des comptes
        recharge_table_produit();

        function recharge_table_produit() {
            $.mobile.loading('show');
            var ct = '<?= SITE_URL ?>loadFile/produit.php';
            $('#list_produit').load(ct, {},
                function(html) {
                    $(this).html(html).trigger('create');
                    produittable
                    $.mobile.loading('hide');
                });
        }

        if (parseInt(idproduit) != 0)
            recharge_file_produit(idproduit);

        function recharge_file_produit(idproduit) {
            $.mobile.loading('show');
            var ct = '<?= SITE_URL ?>loadFile/listProduitFiles.php';
            $('#list_produit_img').empty().load(ct, {
                    idproduit: idproduit
                },
                function(html) {
                    $(this).html(html).trigger('create');
                    $.mobile.loading('hide');
                });
        }

    });
</script>