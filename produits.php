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

if (isset($submitprodfile)) {

    if (empty($id_produit_file)) {

        $error = sms_error("Veuillez bien uploader le fichier");
        $response['feedback'] = $error;

    } else {

        $filesArr = $_FILES["uploadProdFile"];

        $fileNames = array_filter($filesArr['name']);

        $date_du_jour = date("YmdHis");

        if (empty($fileNames)) {

            $response['feedback'] = sms_error("Veuillez selectionner au moins un fichier!");

        } else {

            $mon_dossier1 = "images/Produits";
            $mon_dossier2 = "images/Produits";


            if (!file_exists($mon_dossier1)) {
                mkdir($mon_dossier1, 0777, true);
            }

            $nb_img = 0;
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
                    $nb_img++;
                    ProduitImages::insertImgProd($id_produit_file, $file, $lien, $ext);
                }
            }

            $success = $save_success_msg;
            //Pas d'Erreur d'insertion, le script continue
            $response['feedback'] = 0;
            $response['idproduit'] = $id_produit_file;
            $response['nbprod'] = $nb_prod + $nb_img;
            $response['response'] = $success;
        }
    }

    print json_encode($response);
    exit();
}

if (isset($submitproduit)) {

    if (empty($cat_prod) || empty($nom_prod) || empty($prix_prod) ) {

        $error = sms_error("Veuillez remplir les Champss");

        $response['feedback'] = $error;

    } else {

        if (isset($id_prod) && !empty($id_prod)) {

            ProduitImages::updateProd($cat_prod, $nom_prod, $desc_prod, $prix_prod, $prix_promo_prod,$id_prod);
            
        } else {

            ProduitImages::insertProd($_SESSION['idCptVend'], $cat_prod, $nom_prod, $desc_prod, $prix_prod, $prix_promo_prod);

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
                            <?php include_once 'form/produits.php'; ?>
                        </div>
                    </div>
                </td>
                <td style="width:44%; vertical-align: top;">
                    <div id="contentajoutfile" class="cache" style="width: 100%; border: 1px solid grey; border-radius: 5px;">
                        <div id="" style="margin:10px 0px; width: 100%;">
                            <?php include_once  'form/produitFiles.php'; ?>
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
        $("#cat_prod").chosen();
        //Ouverture du formulaire
        $("#ajout_produit").click(function() {
            if (!$("#contentajoutprod").hasClass('cache')) {
                $("#cat_prod").val("").trigger('chosen:updated');
                $("#addprod #actualiseForm").click();
                $("#contentajoutprod").addClass('cache');

            } else {
                $("#contentajoutprod").removeClass('cache');
                $("#contentajoutfile").addClass('cache');
                $('#list_produit_img').empty();
            }
        });


        //validation des images du voiture
        $('#addprodfile').submit(function(event) {
            var percent = 0;
            //var fData = new FormData($("#addprodfile")[0]);
            $.ajax({
                type: "POST",
                url: 'produits.php',
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                dataType: "json",
                beforeSend: function() {
                    $('#uploadSubmit').attr("disabled", "disabled");
                    $('#addprodfile').css("opacity", ".5");
                },
                xhr: function() {
                    //upload Progress
                    var xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', function(event) {
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
                            $("#addprodfile #uploadProdFile").val("").trigger("create");
                            recharge_file_produit(objet.idproduit);
                            $('#addprodfile').css("opacity", "");
                            $("#progress-wrp" + " .progress-bar").css("width", +percent + "%");
                            $("#progress-wrp" + " .status").text(percent + "%");
                            //$("#progress-wrp").addClass('cache');
                            $("#uploadSubmit").removeAttr("disabled");
                            $("#addprodfile #nb_prod").val(objet.nbprod);

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
        $("#uploadProdFile").change(function() {
            //var ext_file = $("#ext_chap_file").val();
            var nb = $("#addprodfile #nb_prod").val();
            console.log(nb);
            var reste = 8- parseInt(nb);

            if (this.files.length > reste) {
                alert('Vous ne pouvez pas selectionner plus de 08 images');
                $("#uploadProdFile").val('');
                return false;
            }else{
                for (i = 0; i < this.files.length; i++) {
                    var file = this.files[i];
                    var fileType = file.type;
                    if (!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]))) {
                        alert('Svp, Veuillez selectionner que des fichiers JPG, JPEG et PNG pour envoyer.');
                        $("#uploadProdFile").val('');
                        return false;
                    }
                }
            }
        });

        //Chargement des files
        $('#addprod').submit(function(event) {
            $('#addprod').css("opacity", ".5");
            $('#uploadSubmit').attr("disabled", "disabled");
            params = $("#addprod").serialize();
            AjaxLoader("produits.php", params + '&submit=yes', $('#addprod .messageBox'), function() {
                setTimeout(function() {
                    $.mobile.loading("hide");
                    recharge_table_produit();
                    $('#addprod .messageBox').html("").trigger("create");
                    $('#addprod').css("opacity", "");
                    $("#uploadSubmit").removeAttr("disabled");
                    //$("#createcompte #id_client").html("").trigger("chosen:updated");
                    $("#addprod #actualiseForm").click();
                    $("#cat_prod").val("").trigger('chosen:updated');

                }, 4000);
                //return false;
            });
            setTimeout(function() {
                $('#addprod').css("opacity", "");
                $("#uploadSubmit").removeAttr("disabled");
            }, 4000);
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