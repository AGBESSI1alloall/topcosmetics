<?php
include_once 'loader.php';

$password = hash('sha256', 'admin');
$sql ="UPDATE user SET pwdUser=? WHERE idUser=1";
$DB->query($sql, [$password]);

//8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918
//8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918
/*SELECT * FROM user JOIN compte_vendeur USING(idCptVend) 
    WHERE nomCptVend='All' AND emailUser='alladmin@gmail.com' AND pwdUser='8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918' AND etatUser=1 AND etatCptVend=1 */