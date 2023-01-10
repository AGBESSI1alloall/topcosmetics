<?php 
include_once 'loader.php';


$response = array('commande' => "");

if (isset($nbcomd) && $nbcomd == "nbcomd") {

    //Gestion des commandes de ventes ou location
    $nbcmvent = CommandeAchat::nbCommandeNonLue();

    $response['commande'] = $nbcmvent;

    print json_encode($response);
    exit();
}