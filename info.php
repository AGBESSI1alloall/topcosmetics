<?php

//Attributs HTML de Jquery Mobile
$alarm_icon_attr = " data-theme='c' data-rel='dialog' data-role='button' data-icon='alert' data-iconpos='notext' data-mini='true' ";
$alarm_geozone_attr = " data-theme='c' data-rel='dialog' data-role='button' data-icon='at-map' data-iconpos='notext' data-mini='true' ";
$edit_icon_attr = " data-theme='b' data-rel='dialog' data-role='button' data-icon='edit' data-iconpos='notext' data-mini='true' ";
$detail_icon_attr = " data-theme='c' data-rel='dialog' data-role='button' data-icon='bars' data-iconpos='notext' data-mini='true' ";
$useredit_icon_attr = " data-theme='c' data-rel='dialog' data-role='button' data-icon='at-useredit' data-iconpos='notext' data-mini='true' ";
$affect_icon_attr = " data-theme='c' data-rel='dialog' data-role='button' data-icon='at-grid' data-iconpos='notext' data-mini='true' ";
$delete_icon_attr = " data-theme='c' class='delete' data-rel='dialog' data-role='button' data-icon='delete' data-iconpos='notext' data-mini='true' data-inline='true'  ";
$active_icon_attr = " data-theme='c' class='active' data-rel='dialog' data-role='button' data-icon='check' data-iconpos='notext' data-mini='true' data-inline='true'  ";
$block_icon_attr = " data-theme='c' class='delete' data-rel='dialog' data-role='button' data-icon='forbidden' data-iconpos='notext' data-mini='true' data-inline='true'  ";
$red_icon_attr = " data-theme='c' data-role='button' data-iconpos='notext' data-mini='true' ";
$success_icon_attr = " data-icon='check'  data-theme='c' data-role='button' data-inline='true' data-iconpos='notext' data-mini='true' ";
$lock_icon_attr = " data-theme='c' data-rel='dialog' data-role='button' data-icon='at-lock' data-iconpos='notext' data-mini='true' ";
$tracked_icon_attr = " data-icon='at-aerial' data-theme='c' data-role='button' data-iconpos='notext' data-mini='true' ";
$untracked_icon_attr = " data-icon='at-aerial-fail' data-theme='c' data-role='button' data-iconpos='notext' data-mini='true' ";
$icon_attr = " data-theme='c' data-rel='dialog' data-role='button' data-iconpos='notext' data-mini='true' ";
$icon_attr_notheme = " data-rel='dialog' data-role='button' data-iconpos='notext' data-mini='true' ";

$edit_pass_word = " data-theme='b' data-rel='dialog' data-role='button' data-icon='check' data-iconpos='notext' data-mini='true' ";
//Icones
$successIcon = "<a href='#' class='successIcon' $success_icon_attr ></a>";
$deleteIcon = "<a href='#' class='gtdeleteLink delete' $delete_icon_attr ></a>";
//
$errorImg = "<a href='#' class='at-delete' data-icon='alert'  data-theme='c' data-role='button' data-inline='true' data-iconpos='notext' data-mini='true' ></a>";
$successImg = "<a href='#' class='successIcon simple-btn' data-icon='check'  data-theme='c' data-role='button' data-inline='true' data-iconpos='notext' data-mini='true' ></a>";
$infoImg = "<a href='#' data-icon='info'  data-theme='c' data-role='button' data-inline='true' data-iconpos='notext' data-mini='true' ></a>";

$rupture_icon_attr = " data-theme='c' data-rel='dialog' data-role='button' data-icon='alert' data-iconpos='notext' data-mini='true' data-inline='true'  ";

$no_data_infos = "<br /><p class='infos' style='max-width:600px'>$infoImg Aucun enregistrement</p>";

function sms_error($message) {
    return "<p style='color:red; text-align:center; border:1px solid red; border-radius:6px;margin:5px 0px;padding:8px 0px;'>"
            . "<a href='#' data-role='button' data-icon='alert' data-iconpos='notext' data-inline='true'></a>" .
            "$message<p>";
}

function sms_success($message) {
    return "<p style='color:green; text-align:center; border:1px solid green; border-radius:6px;margin:5px 0px;padding:8px 0px;'>"
            . "<a href='#' data-role='button' data-icon='alert' data-iconpos='notext' data-inline='true'></a>" .
            "$message<p>";
}

function random($nbr) {
    $chn = '';
    for ($i = 1; $i <= $nbr; $i++)
        $chn .= chr(floor(rand(0, 25) + 97));
    return $chn;
}

function n_random($digits) {
    return rand(pow(10, $digits - 1) - 1, pow(10, $digits) - 1);
}

//Tronquer un texte
function tronque($chaine, $max) {
    // Nombre de caractère
    if (strlen($chaine) >= $max) {
        // Met la portion de chaine dans $chaine
        $chaine = substr($chaine, 0, $max);
        // position du dernier espace
        $espace = strrpos($chaine, " ");
        // test si il ya un espace
        if ($espace)
        // si ya 1 espace, coupe de nouveau la chaine
            $chaine = substr($chaine, 0, $espace);
        // Ajoute ... à la chaine
        $chaine .= '...';
    }
    return $chaine;
}

function coupetexte($ch, $nb = 0) {
    return substr($ch, $nb);
}

function datefrancais($date) {
    $date = date_create("$date");
    $d = date_format($date, 'd/m/Y H:i:s');
    return $d;
}

function Newdate($date) {
    $date = date_create("$date");
    $d = date_format($date, 'd/m/Y');
    return $d;
}
//Conversion date
function dateDiff($date1, $date2) {
    $date1 = strtotime($date1);
    $date2 = strtotime($date2);

    if ($date1 > $date2) {
        $diff = abs($date1 - $date2); // abs pour avoir la valeur absolute, ainsi éviter d'avoir une différence négative
        $retour = array();

        $tmp = $diff;
        $s = $tmp % 60;
        $retour['second'] = str_pad($s, 2, '0', STR_PAD_LEFT);

        $tmp = floor(($tmp - $retour['second']) / 60);
        $m = $tmp % 60;
        $retour['minute'] = str_pad($m, 2, '0', STR_PAD_LEFT);

        $tmp = floor(($tmp - $retour['minute']) / 60);
        $h = $tmp % 24;
        $retour['hour'] = str_pad($h, 2, '0', STR_PAD_LEFT);

        $tmp = floor(($tmp - $retour['hour']) / 24);
        $retour['day'] = str_pad($tmp, 3, '0', STR_PAD_LEFT);
    } else {
        $retour['second'] = str_pad(0, 2, '0', STR_PAD_LEFT);
        $retour['minute'] = str_pad(0, 2, '0', STR_PAD_LEFT);
        $retour['hour'] = str_pad(0, 2, '0', STR_PAD_LEFT);
        $retour['day'] = str_pad(0, 3, '0', STR_PAD_LEFT);
    }

    return $retour;
}

$save_success_msg = "<p style='color:green; text-align:center; border:1px solid green; border-radius:6px;margin:5px 0px;padding:8px 0px;'>"
        . "<a href='#' data-role='button' data-icon='alert' data-iconpos='notext' data-inline='true'></a>" .
        "Les données sont bien enrégistrées!<p>";

$erreur_msg = "<p style='color:red; text-align:center; border:1px solid red; border-radius:6px;margin:5px 0px;padding:8px 0px;'>"
        . "<a href='#' data-role='button' data-icon='alert' data-iconpos='notext' data-inline='true'></a>" .
        "Veillez remplir tous les champs<p>";

$erreur_file1 = "<p style='color:red; text-align:center; border:1px solid red; border-radius:6px;margin:5px 0px;padding:8px 0px;'>"
        . "<a href='#' data-role='button' data-icon='alert' data-iconpos='notext' data-inline='true'></a>" .
        "Le fichier est trop gros<p>";

$erreur_file2 = "<p style='color:red; text-align:center; border:1px solid red; border-radius:6px;margin:5px 0px;padding:8px 0px;'>"
        . "<a href='#' data-role='button' data-icon='alert' data-iconpos='notext' data-inline='true'></a>" .
        "L'extension de votre fichier n'est pas acceptée.<p>";

$edit_erreur_msg = "<p style='color:red; text-align:center; border:1px solid red; border-radius:6px;margin:5px 0px;padding:8px 0px;'>"
        . "<a href='#' data-role='button' data-icon='alert' data-iconpos='notext' data-inline='true'></a>" .
        "Les données sont incorrectes<p>";

$edit_success_msg = "<p style='color:green; text-align:center; border:1px solid green; border-radius:6px;margin:5px 0px;padding:8px 0px;'>"
        . "<a href='#' data-role='button' data-icon='alert' data-iconpos='notext' data-inline='true'></a>" .
        "Les données sont bien modifiée!<p>";

$delete_success_msg = "<p style='color:green; text-align:center; border:1px solid red; border-radius:6px;margin:5px 0px;padding:8px 0px;'>"
        . "<a href='#' data-role='button' data-icon='alert' data-iconpos='notext' data-inline='true'></a>" .
        "Les données sont bien supprimées!<p>";

$erreur_doublons = "<p style='color:red; text-align:center; border:1px solid red; border-radius:6px;margin:5px 0px;padding:8px 0px;'>"
        . "<a href='#' data-role='button' data-icon='alert' data-iconpos='notext' data-inline='true'></a>" .
        "Les données existent déjà, Merci!<p>";

$erreur_occupe = "<p style='color:red; text-align:center; border:1px solid red; border-radius:6px;margin:5px 0px;padding:8px 0px;'>"
        . "<a href='#' data-role='button' data-icon='alert' data-iconpos='notext' data-inline='true'></a>" .
        "Les données sont utilisées ailleurs impossible!, Merci!<p>";
//Titres de listes (pour éviter les accès en boucle à la BD de t() --------------------

$TITLE_MODIF_INFOS = 'Modifier les infos';
$TITLE_AJOUT_DEPOTS = 'AJOUTER UN DEPOT';
$TITLE_MODIF_PERIODE = 'Modifier la période';
$ATR_PASSWORD = 'Changer le Mot de passe';
$TITLE_DETAIL_PAIEMENT = 'Detail des frais de Module';

function titles($val) {
    return $val;
}

$TITLE_PAIEMENT = "Payer la livraison";
$TITLE_CONFIRM_PAIEMENT = "Confirmer le paiement";
$TITLE_MODIF = 'Modifier';
$TITLE_DELETE = 'Supprimer';
$TITLE_ACTIVE = 'Activer';
$TITLE_DESACTIVE = 'Désactiver';
$TITLE_BLOCK = 'Bloquer';
$TITLE_MODIF_PWD = 'Changer le mot de passe';
$TITLE_MODIF_RIGHTS = 'Modifier les droits';
$TITLE_INFOS = 'infos';
$TITLE_MAP = 'carte';
$TITLE_INFOS_DETAIL = 'info détaillée';
$TITLE_MAP_POSITION = 'position sur carte';
$TITLE_PARAMS_ALARM = 'paramétrage des alarmes';
$TITLE_AFFICHER = 'Afficher';
$TITLE_FERMER = 'Fermer';
$TITLE_AJOUT = 'REMBOURSER';
$SEP_ARROW_R = "<span style='color:#555555'>&#x25B6;</span>";
$TITLE_DETAILS_APP = "Détail ...";
$TITLE_VALIDE = "Valider ...";
$TITLE_ANNULER = "Annuler ...";
$TITLE_NON_RUPTURE = "Mettre rupture";
$TITLE_RUPTURE = "Enlever en rupture";
$TITLE_AFFEC_DR = "Affectation des droits";

//Attribut de colonne th pour la création de filtre datatables
$dt_filter_input_reg = "at-dt-filter";
$dt_filter_input = "at-dt-filter='wordBeginsWith=false'";
$dt_filter_select = "at-dt-filter='type=select'";

function convertDate($format, $date) {
    $date = DateTime::createFromFormat($format, $date);
    $result = $date->format('d/m/Y');
    return $result;
}

//Tableaau securisé dans le code

$typeCarte = [
    'CNI' => 'Cate d\'Identité',
    'CP' => 'Passport',
    'CE' => 'Carte d\'Electeur'
];

//Domaijne requise
$domaineActivite = [
    'informaticien' => 'INFORMATICIEN',
    'agent' => 'AGENT COMMERCIAL',
    'Caissier' => 'CAISSIER',
    'directeur' => 'DIRECTEUR',
    'comptable' => 'COMPTABLE',
    'gestionnaire' => 'GESTIONNAIRE DE COMPTE'
];

//Niveau Employe
$niveauEmploye = [
    'cepd' => 'CEPD',
    'bepc' => 'BEPC',
    'cfa' => 'CFA',
    'cap' => 'CAP',
    'bt' => 'BT',
    'bts' => 'BTS',
    'licence' => 'LICENCE',
    'master' => 'MASTER',
    'doctorat' => 'DOCTORAT'
];

$typeCompte = [
    '1' => 'SIMPLE',
    '2' => 'BLOQUE'
];

//Quelques nationalite
$nationalite = [
    'togolaise' => 'TOGOLAISE',
    'beninoise' => 'BENINOISE',
    'ghaneenne' => 'GHANEENNE',
    'ivoirienne' => 'IVOIRIENNE',
    'burkinabe' => 'BURKINABE',
    'nigerienne' => 'NIGERIENNE',
    'nigerianne' => 'NIGERIANNE',
    'malienne' => 'MALIENNE',
    'gabonnaise' => 'GABONNAISE',
    'camerounaise' => 'CAMEROUNAISE',
    'centrafricaine' => 'CENTRAFRICAINE',
    'guineenne' => 'GUINEENNE',
    'equato guinneenne' => 'EQUATO GUINEENNE',
];

//LA Périodicité des crédits
$periodicite_gen = [
    'm' => 'MENSUELLE',
    't' => 'TRIMESTRIELLE',
    's' => 'SEMESTRIELLE'
];

//Type de taux
$f_type_taux = [
    'g' => 'GENERAL',
    'o' => 'ORDINAIRE'
];

$type_benefice = [
    '0' => "Tous",
    '1' => "Frais Création",
    '2' => "Cotisation Tontine",
    '3' => "Frais Carnet",
    '4' => "Crédit",
    '5' => "Frais Dossier"
];

//Type Transition
$type_transition = [
    '1' => 'Dépôt',
    '2' => 'Retrait'
];

$ext_array = [
    'img' => 'IMG',
    'pdf' => 'PDF'
];

$type_promo = [
    'typeabt' => 'Type Abt',
    'duree' => 'Durée'
];

$decouverte = [
    '0' => "Non",
    '1' => "Oui"
];