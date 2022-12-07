<?php
include_once '../loader.php';

$listphotovoiture = PhotoVoiture::listPhotoVoiture($idvoiture);

$nt_photo = count($listphotovoiture);
$nb_colums = 8;
$nb_colums = $nt_photo <= $nb_colums ? 4 : $nb_colums;

$maw_width = "max-width:120px";
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

                        if (array_key_exists($j, $listphotovoiture)) {
                            $camera = empty($listphotovoiture[$j]["lienPhoto"]) ? "" : "<img src='../MobileEzRentalCars/" . $listphotovoiture[$j]["lienPhoto"] . "' style='width:100%;$maw_width'/>";
                            
                            $btn_delete = "<a class='delmg' href='form/deleteFile.php?delete=true&idvoiture={$listphotovoiture[$j]['idVoi']}&iddoc={$listphotovoiture[$j]['idPhoto']}' $delete_icon_attr>$TITLE_DELETE</a>";

                ?>
                            <td <?= $middle ?>>
                                <div class="mon-image">
                                    <a href="#" class="list-voit" idVoi="<?= $listphotovoiture[$j]['idPhoto'] ?>"><?= $camera ?></a>
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