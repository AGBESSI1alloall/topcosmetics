<?php
session_start();
extract($_REQUEST);

require_once '_autoloader.php';
include_once 'redirectToHttps.php';
require_once 'vendor/autoload.php';
require_once 'server.php';
require_once 'info.php';

//Connexion de base de donnée
$DB = new Db(DBHost, DBPort, DBName, DBUser, DBPassword);

//Contente de loader
$files_project = ["login.php","logout.php","loader.php","index.php","dynamicSelect.php"];

//Val_util
if(isset($_SESSION['data'])){
$id_util = $_SESSION['data'][0]['idUser'];
$droit_util = $_SESSION['data'][0]['typeUser'];
$_SESSION['idUser'] = $id_util;
$_SESSION['typeUser'] = $droit_util;
$_SESSION['idCptVend'] = $_SESSION['data'][0]['idCptVend'];
}


$last_file = $lass_dossier = "";
$last_file = basename($_SERVER['SCRIPT_FILENAME']);
$lass_dossier = $last = dirname($_SERVER['SCRIPT_FILENAME']);
$lass_dossier = str_replace(' ', '', $lass_dossier);


if (!empty($last_file) && $last_file != "index.php") {

    $_SESSION['lastpage'] = $last_file;

    if ($_SESSION['lastpage'] != "" && (($lass_dossier == "") || ($lass_dossier == "C:/xampp/htdocs/topcosmetics"))) {
        
        if (isset($_SESSION['data']) && $_SESSION['data'] != NULL) {
            
            $result = Lien::lineLien($id_util);
            $nbr_valid = count($result);
            
            if ($nbr_valid > 0) {
                if (!in_array($_SESSION['lastpage'], $files_project)) {
                    Lien::updateLien($_SESSION['lastpage'], $id_util);
                }
            } else {
                Lien::insertLien($id_util, $_SESSION['lastpage']);
            }
        }
    }
}

$session_espire = 0;
if (isset($_SESSION['last_login_timestamp']) && $_SESSION['last_login_timestamp'] != NULL) {
    if ((time() - $_SESSION['last_login_timestamp']) > 2000000) {

        $result = Connexion::lineConnexion($id_util);

        $count_id = count($result);
        if ($count_id != 0) {
            $id_conn = $result[0]['idConn'];
            Connexion::updateConnexion($id_conn);
        }
        unset($_SESSION['loggedIN']);
        $session_espire = 1;
        $_SESSION['last_login_timestamp'];
        session_destroy();
        header('Location:login.php');
        exit();
    } else {
        $_SESSION['last_login_timestamp'] = time();
    }
}
