<?php 
class Banniere extends GeneralClass {

    const SQL_TABLE_BANNIERE = "banniere_compte";
    const SQL_TABLE_CPT = "compte_vendeur";

    const SQL_INSERT_BANNIERE = "INSERT INTO ".self::SQL_TABLE_BANNIERE."(idCptVend, nomBanCpt, lienBanCpt, extBanCpt) VALUES(?,?,?,?)";
    const SQL_UPDATE_BANNIERE = "UPDATE ".self::SQL_TABLE_BANNIERE." SET nomBanCpt=?, lienBanCpt=?, extBanCpt=?, dateEditBanCpt=NOW() WHERE idBanCpt=?";
    const SQL_SELECT_BANNIERE = "SELECT * FROM ".self::SQL_TABLE_BANNIERE." bn JOIN ".self::SQL_TABLE_CPT." cpt USING(idCptVend) WHERE idCptVend=?";
    const SQL_DELETE_BANNIERE = "DELETE FROM  ".self::SQL_TABLE_BANNIERE." WHERE idBanCpt=?";

    public static function insertBanniere($idCptVend, $nomBanCpt, $lienBanCpt, $extBanCpt){
        global $DB;

        $DB->query(self::SQL_INSERT_BANNIERE, [$idCptVend, $nomBanCpt, $lienBanCpt, $extBanCpt]);
    }

    public static function updateBanniere($nomBanCpt, $lienBanCpt, $extBanCpt,$idBanCpt){
        global $DB;

        $DB->query(self::SQL_UPDATE_BANNIERE, [$nomBanCpt, $lienBanCpt, $extBanCpt,$idBanCpt]);
    }

    public static function listBanniere($idCptVend){
        global $DB;

         return $DB->query(self::SQL_SELECT_BANNIERE, [$idCptVend]);
    }

    public static function deleteBanniere($idBanCpt){
        global $DB;

        $DB->query(self::SQL_DELETE_BANNIERE, [$idBanCpt]);
    }
}