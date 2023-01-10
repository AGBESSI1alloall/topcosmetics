<?php 
include_once '../loader.php';

if($_SESSION['idCptVend'] == 1)
    include_once 'ventesGenerals.php';
else
    include_once 'ventesCompte.php';