<?php

//Connection à la base de donnees
//$is_local = file_exists($_SERVER['DOCUMENT_ROOT'] . '/mgadmin/local-server');

if ($is_local) {

    define('DBHost', 'localhost');
    define('DBPort', 3306);
    define('DBName', 'topcosmetics');
    define('DBUser', 'root');
    define('DBPassword', 'Christian@2018');
    
} else {

    define('DBHost', '185.98.131.176');
    define('DBPort', 3306);
    define('DBName', 'topcosmetics');
    define('DBUser', 'root');
    define('DBPassword', 'dstech@Top');
    
}

?>