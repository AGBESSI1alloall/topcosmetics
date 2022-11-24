<?php

class User extends GeneralClass {

    const SQL_TABLE_USER = "user";
    const SQL_TABLE_CV = "compte_vendeur";

    const SQL_FOR_ONE_USER = "SELECT * FROM ".self::SQL_TABLE_USER." JOIN ".self::SQL_TABLE_CV." USING(idCptVend) 
    WHERE idUser=?";

    public static function lineForOneUser($iduser){
        global $DB;

        return $DB->row(self::SQL_FOR_ONE_USER, [$iduser]);

    }
}