<?php 

include_once '../loader.php';

$date = new DateTime();
$dateDeb = $date -> format('Y-m-01');
$dateFin = $date -> format('Y-m-t');

$datedeb = empty($datedeb) ? $dateDeb." 00:00" : $datedeb." 00:00";
$datefin = empty($datefin) ? $dateFin." 23:59" : $dateFin." 23:59";

if($_SESSION['idCptVend'] == 1)
    include_once 'ventesHistoriquesGenerals.php';
else
    include_once 'ventesHistoriquesCompte.php';

