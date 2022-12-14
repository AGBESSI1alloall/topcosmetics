<?php
include_once '../loader.php';

$listphotoproduit = ProduitImages::listImgProd($idproduit);

$nt_photo = count($listphotoproduit);

$nb_colums = 8;

$nb_colums = $nt_photo <= $nb_colums ? 4 : $nb_colums;

$maw_width = "max-width:120px";
$_smiddle = "style='vertical-align:middle; width:24%; text-align:center;'";
$_middle = $nt_photo <= $nb_colums ? "style='vertical-align:middle; width:24%; text-align:center;'" : "style='vertical-align:middle; width:12%; text-align:center;'";

$message = sms_error('Aucune images pour cette voiture, Merci');

?>

<div id="list_files">
    <?php
    if ($nt_photo) {
    ?>
        <table width='100%' align='center' id='tableau_photo' class='blocList'>
            <tbody>
                <?php
                for ($i = 0; $i < $nt_photo; $i += $nb_colums) {

                    echo "<tr class='rot-start-head' $_middle>";

                    for ($j = $i; $j < ($i + $nb_colums); $j++) {

                        if (array_key_exists($j, $listphotoproduit)) {
                            $camera = empty($listphotoproduit[$j]["lienImgProd"]) ? "" : "<img src='" . $listphotoproduit[$j]["lienImgProd"] . "' style='width:100%;$maw_width'/>";
                            
                            $btn_delete = "<a class='delmg' href='form/deleteFile.php?delete=true&idproduit={$listphotoproduit[$j]['idProd']}&iddoc={$listphotoproduit[$j]['idImgProd']}' $delete_icon_attr>$TITLE_DELETE</a>";

                ?>
                            <td <?= $_middle ?>>
                                <div class="mon-image">
                                    <a href="#" class="list-voit" idimage="<?= $listphotoproduit[$j]['idImgProd'] ?>"><?= $camera ?></a>
                                    <p><?=$btn_delete?></p>
                                </div>
                            </td>
                <?php
                        } else {
                            echo "<td $_middle></td>";
                        }
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    <?php
    } else {
        echo $message;
    }
    ?>
</div>
<style type="text/css">
    table#tableau_photo td div.mon-image img:hover{
        transform: scale(2);
        /*z-index: 9999;*/
    }
</style>