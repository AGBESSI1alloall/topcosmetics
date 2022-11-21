<?php

class Autoload {

    public static function connectClass() {
        //parcourrir Toute les class
        spl_autoload_register(array(__CLASS__, "load"));
    }

    public static function load($classe) {
        
        $file = 'library/Entity/' . $classe . '.php';
        //error_log($file);
        require_once($file);
        
    }

}

Autoload::connectClass();

?>