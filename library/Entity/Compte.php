<?php
class Compte extends GeneralClass {
    const SQL_TABLE_CV = "compte_vendeur";
    const SQL_TABLE_CC = "categorie_compte";
    const SQL_TABLE_CAV = "categorie_vente";



    const SQL_ALL_COMPTE = "SELECT * FROM ".self::SQL_TABLE_CV." ORDER BY idCptVend";
    const SQL_LINE_COMPTE = "SELECT * FROM ".self::SQL_TABLE_CV." WHERE idCptVend=? AND etatCptVend=1";
    const SQL_UPDATE_COMPTE = "UPDATE ".self::SQL_TABLE_CV." SET nomCptVend=?, sloganCptVend=?, telCptVend=?, emailCptVend=?, adresCptVend=?, 
    descCptVend=?, dateEditCreateCptVend=NOW() WHERE idCptVend=?";
    const SQL_DESACTIVE_COMPTE = "UPDATE ".self::SQL_TABLE_CV." SET etatCptVend=0 WHERE idCptVend=?";
    const SQL_ACTIVE_COMPTE = "UPDATE ".self::SQL_TABLE_CV." SET etatCptVend=1 WHERE idCptVend=?";

    const SQL_CAT_COMPTE = "SELECT * FROM ".self::SQL_TABLE_CAV." JOIN ".self::SQL_TABLE_CC." USING(idCatVent) WHERE idCptVend=?";

    public static function listCategorieCompte($idCptVend){
        global $DB;

        $result = $DB->query(self::SQL_CAT_COMPTE, [$idCptVend]);

        $result = array_column($result, 'libCatVent');

        $result = count($result)? implode(',', $result) : "Toutes";

        return $result; 

    }

    public static function listOfCompte(){
        global $DB;

        return $DB->query(self::SQL_ALL_COMPTE);
    }

    public static function lineOfCompte($idCptVend){
        global $DB;

        return $DB->query(self::SQL_LINE_COMPTE, [$idCptVend]);
    }

    public static function updateCompte($nomCptVend, $sloganCptVend, $telCptVend, $emailCptVend, $adresCptVend, $descCptVend, $idCptVend){
        global $DB;

        $DB->query(self::SQL_UPDATE_COMPTE, [$nomCptVend, $sloganCptVend, $telCptVend, $emailCptVend, $adresCptVend, $descCptVend, $idCptVend]);
    }

    public static function activerCompte($idCptVend){
        global $DB;

        $DB->query(self::SQL_ACTIVE_COMPTE, [$idCptVend]);
    }

    public static function desactiveCompte($idCptVend){
        global $DB;

        $DB->query(self::SQL_DESACTIVE_COMPTE, [$idCptVend]);
    }
}