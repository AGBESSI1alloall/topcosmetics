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
$files_project = ["login.php","logout.php","loader.php","index.php"];

//Val_util
if(isset($_SESSION['data'])){
$id_util = $_SESSION['data'][0]['idUser'];
$droit_util = $_SESSION['data'][0]['typeUser'];
$_SESSION['idUser'] = $id_util;
$_SESSION['typeUser'] = $droit_util;
$_SESSION['idCptVend'] = $_SESSION['data'][0]['typeUser'];
}


$last_file = $lass_dossier = "";
$last_file = basename($_SERVER['SCRIPT_FILENAME']);
$lass_dossier = $last = dirname($_SERVER['SCRIPT_FILENAME']);
$lass_dossier = str_replace(' ', '', $lass_dossier);


if (!empty($last_file) && $last_file != "index.php") {

    $_SESSION['lastpage'] = $last_file;

    if ($_SESSION['lastpage'] != "" && (($lass_dossier == "//var/www/mangadojo.net/htdocs/topcosmetics") || ($lass_dossier == "C:/xampp/htdocs/topcosmetics"))) {
        
        if (isset($_SESSION['data']) && $_SESSION['data'] != NULL) {
            
            $requet = "SELECT * FROM lien WHERE idUser=?";
            $result = $DB->query($requet, [$id_util]);
            $nbr_valid = count($result);
            
            if ($nbr_valid > 0) {
                if (!in_array($_SESSION['lastpage'], $files_project)) {
                    $sql = "UPDATE lien SET lien = ? WHERE idUser =?";
                    $DB->query($sql, [$_SESSION['lastpage'], $id_util]);
                }
            } else {
                $sql = "INSERT INTO lien(idUser,lien) VALUES(?,?)";
                $DB->query($sql, array($id_util, $_SESSION['lastpage']));
            }
        }
    }
}

$session_espire = 0;
if (isset($_SESSION['last_login_timestamp']) && $_SESSION['last_login_timestamp'] != NULL) {
    if ((time() - $_SESSION['last_login_timestamp']) > 2000000) {

        $requet = "SELECT idConn FROM connexion WHERE idUser=? ORDER BY idConn DESC LIMIT 1";
        $result = $DB->query($requet, [$id_util]);
        $id_conn = $result[0]['idConn'];
        $count_id = count($id_conn);
        if ($count_id != 0) {
            $sql = "UPDATE connexion SET dateDeconn = NOW() WHERE idConn = ?";
            $DB->query($sql, array($id_conn));
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
