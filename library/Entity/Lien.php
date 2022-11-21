<?php

class Lien extends GeneralClass {

    const SQL_TABLE_LIEN = "lien";

    const SQL_SELECT_LIEN_PERSO ="SELECT * FROM ".self::SQL_TABLE_LIEN." WHERE idUser=?";
    const SQL_UPDATE_LIEN = "UPDATE ".self::SQL_TABLE_LIEN." SET lien = ? WHERE idUser =?";
    const SQL_INSERT_LIEN = "INSERT INTO ".self::SQL_TABLE_LIEN."(idUser,lien) VALUES(?,?)";

    public static function lastLien($iduser){
        global $DB;

        $result = $DB->query(self::SQL_SELECT_LIEN_PERSO, [$iduser]);

        $nbr_liens = count($result);

        return $nbr_liens > 0 ? $result[0]['lien'] : "user.php";

    }

    public static function lineLien($iduser){
        global $DB;

        return $result = $DB->query(self::SQL_SELECT_LIEN_PERSO, [$iduser]);
    }

    public static function updateLien($lien, $iduser){
        global $DB;

        $DB->query(self::SQL_UPDATE_LIEN, [$lien, $iduser]);
    }

    public static function insertLien($iduser, $lien){
        global $DB;

        $DB->query(self::SQL_INSERT_LIEN, [$iduser, $lien]);
    }

}