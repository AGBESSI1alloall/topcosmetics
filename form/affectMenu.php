<?php
include_once '../loader.php';

if (isset($affect)) {    
    $id_user = $iduser;
}

if (isset($submit)) {

    if (empty($gs_admin) && empty($gs_vente) && empty($gs_user)) {

        $response['feedback'] = $erreur_msg;
        //GOTO erreurForMod;
    } else {
        
        Menu::deleteMenuUser($id_user);

        if(isset($gs_admin) && count($gs_admin)){
            foreach ($gs_admin as $l) {
                Menu::insertMenuUser($id_user, 4, $l);
            }
        }
        
        if(isset($gs_user) && count($gs_user)){
            foreach ($gs_user as $l) {
                Menu::insertMenuUser($id_user, 3, $l);
            } 
        }

        if(isset($gs_vente) && count($gs_vente)){
            foreach ($gs_vente as $l) {
                Menu::insertMenuUser($id_user, 1, $l);
            } 
        }
        //Pas d'Erreur d'insertion, le script continue
        $response['feedback'] = 0;
        $response['response'] = $save_success_msg;
    }
    //erreurForMod:
    print json_encode($response);
    exit();
}

//Gestion de l'ouverture de callapse
$ad = "g_admin";
$m = "g_message";
$u = "g_user";
$v = "g_vente";

$plie_callapse_admin = User::detectMenu($ad, $id_user) == true ? false : true;
$plie_callapse_vente = User::detectMenu($v, $id_user) == true ? false : true;
$plie_callapse_message = User::detectMenu($m, $id_user) == true ? false : true;
$plie_callapse_user = User::detectMenu($u, $id_user) == true ? false : true;

//Check 
$check_callapse_admin = User::detectMenu($ad, $id_user) == true ? "checked" : "";
$check_callapse_vente = User::detectMenu($v, $id_user) == true ? "checked" : "";
$check_callapse_user = User::detectMenu($u, $id_user) == true ? "checked" : "";
$check_callapse_message = User::detectMenu($m, $id_user) == true ? "checked" : "";
//Gestion de checked des checkboxs

//gestion
$vente1 = User::detectMenu($v, $id_user, 1, "d") == true ? "checked" : "";
$vente2 = User::detectMenu($v, $id_user, 2, "d") == true ? "checked" : "";

//User
$user1 = User::detectMenu($u, $id_user, 3, "d") == true ? "checked" : "";

//Admin
$admin1 = User::detectMenu($ad, $id_user, 4, "d") == true ? "checked" : "";

?>

<div data-role="page" id="standard" data-add-back-btn="true">
    <div data-role="header">
        <h1>Affectation des Menus</h1>
    </div>
    <div data-role="content">
        <form method="post" action="" id="affectmenu" style="margin:10px 20px;">
            <input type="hidden" name="id_user" id="id_user" value="<?= $id_user ?>" />
            <div id="generalaffect" style="width: 100%">
                <div class="acl-line">
                    <div class="acl-left">
                        <input type="checkbox" name="<?=$v?>" id="<?=$v?>" value="1" <?= $check_callapse_vente ?> class="custom menu-checkbox" style="width: 30px; height: 30px; margin:10px;"> 
                    </div>
                    <div class="acl-right">
                        <div data-role="collapsible" data-collapsed="<?= $plie_callapse_vente ?>" data-corners="true" data-collapsed-icon="arrow-r" data-expanded-icon="arrow-d" data-mini="false" id="aff_menu">
                            <h3>Ventes</h3>
                            <label for="gs_vente" data-corners="false"><input type="checkbox" name="gs_vente[]" class="g_vente" data-mini="true" value="1" <?= $vente1 ?>>En cours & Traitée</label>
                            <label for="gs_vente" data-corners="false"><input type="checkbox" name="gs_vente[]" class="g_vente" data-mini="true" value="2" <?= $vente2 ?>>Historiques</label>
                        </div>
                    </div>
                </div>
                <div class="acl-line">
                    <div class="acl-left">
                        <input type="checkbox" name="<?=$u?>" id="<?=$u?>" value="3" <?= $check_callapse_user ?> class="custom menu-checkbox" style="width: 30px; height: 30px; margin:10px;"> 
                    </div>
                    <div class="acl-right">
                        <div data-role="collapsible" data-collapsed="<?= $plie_callapse_user ?>" data-corners="true" data-collapsed-icon="arrow-r" data-expanded-icon="arrow-d" data-mini="false" id="aff_menu">
                            <h3>Utilisateur</h3>
                            <label for="gs_user" data-corners="false"><input type="checkbox" name="gs_user[]" class="g_user" data-mini="true" value="3" <?= $user1 ?>>Utilisateur</label>
                        </div>
                    </div>
                </div>
                <div class="acl-line">
                    <div class="acl-left">
                        <input type="checkbox" name="<?=$ad?>" id="<?=$ad?>" value="4" <?= $check_callapse_admin ?> class="custom menu-checkbox" style="width: 30px; height: 30px; margin:10px;"> 
                    </div>
                    <div class="acl-right">
                        <div data-role="collapsible" data-collapsed="<?= $plie_callapse_admin ?>" data-corners="true" data-collapsed-icon="arrow-r" data-expanded-icon="arrow-d" data-mini="false" id="aff_menu">
                            <h3>Admin</h3>
                            <label for="gs_admin" data-corners="false"><input type="checkbox" name="gs_admin[]" class="g_admin" data-mini="true" value="4" <?= $admin1 ?>>Utilisateurs</label>
                        </div>                                                                                                               
                    </div>
                </div>
            </div>
            <!--Partie de validation-->
            <div style="margin-bottom:10px"></div>
            <div class="messageBox" style="height: auto;"></div>
            <div class="twobutton" style="text-align:center">
                <input id="submit" name="submit" type="hidden" value="Enregistrer" data-mini="true" data-icon="edit" data-theme="b" />
                <a href="#" id="editsubmit" data-icon="edit" data-role="button" data-mini="true" data-icon="back" data-theme="b">Modifier</a>
                <a href="#" id="cancel" data-rel="back" data-role="button" data-mini="true" data-icon="back" data-theme="a">Annuler</a>
            </div>
            <div class="formLine onebutton" style="display:none; text-align:center">
                <a href="#" data-rel="back" id="close" data-role="button" data-mini="true" data-icon="check" data-theme="b">Ok</a>
            </div>
            <div class="formLine onebuttonerror" style="display:none; text-align:center">
                <a href="#" data-rel="back" data-role="button" data-mini="true" data-icon="back" data-theme="a">Fermer</a>
            </div>
        </form>
        <style type="text/css">
            a:hover, a.ui-focus {
                color: black;
            }
            .acl-line > div {
                display:inline-block;
            }
            .acl-line .acl-left{
                vertical-align: top;
                width: 8%;
                position: relative;
                top: 0px;
                right: 5px;
            }
            .acl-line .acl-left label{
                border-radius : 0px;
                font-size: 10px
            }
            .acl-line .acl-left label h3{
                font-size: 10px;
                padding-top : 1px;
                padding-bottom : 1px;
            }
            .acl-line .acl-right{
                width: 80%;
                margin-left: 20px;
            }
            .ui-collapsible-content{
                padding: 0px;
            }
            .ui-collapsible{
                margin-right: -5px;
            }
        </style>
        <script type="text/javascript">
            $(function () {
                $("input.menu-checkbox").change(function () {
                    var name = $(this).attr('name');
                    if ($(this).is(':checked')) {
                        $(this).closest('.acl-line').find('.acl-right #aff_menu').collapsible("option", "collapsed", false);
                        var inputs = $(this).closest('.acl-line').find('.acl-right input[type="checkbox"].' + name);
                        inputs.each(function () {
                            this.checked = true
                        }).checkboxradio('refresh');
                    } else {
                        $(this).closest('.acl-line').find('.acl-right #aff_menu').collapsible("option", "collapsed", true);
                        var inputs = $(this).closest('.acl-line').find('.acl-right input[type="checkbox"].' + name);
                        inputs.each(function () {
                            this.checked = false
                        }).checkboxradio('refresh');
                    }
                });

                //Si tous les sous-menus sont désactivés on désactive le menu
                $(".acl-right input[type='checkbox']").change(function () {
                    //Nombre de sous-menu activés
                    var checked_nb = $(this).closest('.acl-right').find("input[type='checkbox']:checked").length;
                    var menu_checkbox = $(this).closest('.acl-line').find('.acl-left input.menu-checkbox');
                    //
                    if (checked_nb == 0)
                        menu_checkbox.prop('checked', false).checkboxradio('refresh');
                    else
                        menu_checkbox.prop('checked', true).checkboxradio('refresh');
                })

                $("#editsubmit").on('click', function () {
                    params = $("#affectmenu").serialize();
                    AjaxLoader("affectMenu.php", params + '&submit=yes', $('#affectmenu .messageBox'), "", "wholecontent");
                });
            });
        </script>
    </div>
</div>
