<?php 
//libCatVent, descCatVent,	dateCreateCatVent, dateEditCatVent	etatCatVent
class Category extends GeneralClass {

    const SQL_TABLE_CATEGORY = "categorie_vente";
    const SQL_TABLE_CATEGORY_COMPTE = "categorie_compte";

    const SQL_INSERT_CATEGORY = "INSERT INTO ".self::SQL_TABLE_CATEGORY."(libCatVent, descCatVent) VALUES(?,?)";

    const SQL_UPDATE_CATEGORY = "UPDATE ".self::SQL_TABLE_CATEGORY." SET libCatVent=?, descCatVent=?, dateEditCatVent=NOW() WHERE idCatVent=?";

    const SQL_HIDE_CATEGORY = "UPDATE ".self::SQL_TABLE_CATEGORY." SET etatCatVent=0 WHERE idCatVent=?";

    const SQL_DELETE_CATEGORY = "DELETE FROM ".self::SQL_TABLE_CATEGORY." WHERE idCatVent=?";

    const SQL_SELECT_ALL_CATEGORY = "SELECT * FROM ".self::SQL_TABLE_CATEGORY." WHERE etatCatVent=1";

    const SQL_SELECT_LINE_CATEGORY = "SELECT * FROM ".self::SQL_TABLE_CATEGORY." WHERE idCatVent=?";

    const SQL_SELECT_ALL_CATEGORY_COMPTE = "SELECT * FROM ".self::SQL_TABLE_CATEGORY_COMPTE." WHERE idCatVent=?";

    public static function check_categories_comptes($idCatVent){
        global $DB;

        $rest = $DB->query(self::SQL_SELECT_ALL_CATEGORY_COMPTE, [$idCatVent]);

        if(count($rest))
            return true;
        else
            return false;
    }

    public static function listCategory(){
        global $DB;

        return $DB->query(self::SQL_SELECT_ALL_CATEGORY);
    }

    public static function lineCategory($idCatVent){
        global $DB;

        return $DB->row(self::SQL_SELECT_LINE_CATEGORY, [$idCatVent]);
        
    }

    public static function insertCategory($libCatVent, $descCatVent){
        global $DB;

        $DB->query(self::SQL_INSERT_CATEGORY, [$libCatVent, $descCatVent]);
    }

    public static function updateCategory($libCatVent, $descCatVent, $idCatVent){
        global $DB;

        $DB->query(self::SQL_UPDATE_CATEGORY, [$libCatVent, $descCatVent, $idCatVent]);

    }

    public static function deleteCategory($idCatVent){
        global $DB;

        if(self::check_categories_comptes($idCatVent))
            $DB->query(self::SQL_HIDE_CATEGORY, [$idCatVent]);
        else
            $DB->query(self::SQL_DELETE_CATEGORY, [$idCatVent]);

    }
}