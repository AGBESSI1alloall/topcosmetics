<?php

class Connexion extends GeneralClass {

    const SQL_TABLE_CONNEXION = "connexion";

    const SQL_LINE_SELECT_CONNEXION = "SELECT idConn FROM ".self::SQL_TABLE_CONNEXION." WHERE idUser=? ORDER BY idConn DESC LIMIT 1";
    const SQL_UPDATE_CONNEXION = "UPDATE ".self::SQL_TABLE_CONNEXION." SET dateDeconn = NOW() WHERE idConn = ?";
    const SQL_INSERT_CONNEXION ="INSERT INTO ".self::SQL_TABLE_CONNEXION."(idUser,dateConn,dateDeconn) VALUES(?,NOW(),NOW())";

    public static function lineConnexion($iduser){
        global $DB;

        return $result = $DB->query(self::SQL_LINE_SELECT_CONNEXION, [$iduser]);

    }

    public static function updateConnexion($idcon){
        global $DB;

        $DB->query(self::SQL_UPDATE_CONNEXION, [$idcon]);
    }

    public static function insertConnexion($iduser){
        global $DB;

        $DB->query(self::SQL_INSERT_CONNEXION, [$iduser]);
    }
    
}