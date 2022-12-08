<?php
class ProduitImages extends GeneralClass{

    const SQL_TABLE_PRODUIT = "produit";
    const SQL_TABLE_IMG_PRODUIT = "images_produit";
    const SQL_TABLE_ACHAT = "achat";
    const SQL_TABLE_CAT_VENT = "categorie_vente";

    //Consernant le produit
    const SQL_INSERT_PROD = "INSERT INTO ".self::SQL_TABLE_PRODUIT."(idCptVend, idCatVent, nomProd, descProd, prixProd, prixPromoProd) VALUES(?,?,?,?,?,?)";
    const SQL_UPDATE_PROD = "UPDATE ".self::SQL_TABLE_PRODUIT." SET idCatVent=?, nomProd=?, descProd=?, prixProd=?, prixPromoProd=?, dateEditProd=NOW() WHERE idProd=?";
    const SQL_SELECT_ALL_PROD = "SELECT * FROM ".self::SQL_TABLE_PRODUIT." JOIN ".self::SQL_TABLE_CAT_VENT." USING(idCatVent) WHERE etatProd=1 ORDER BY idProd";
    const SQL_SELECT_PROD_CPT = "SELECT * FROM ".self::SQL_TABLE_PRODUIT." JOIN ".self::SQL_TABLE_CAT_VENT." USING(idCatVent) WHERE etatProd=1 AND idCptVend=? ORDER BY idProd";
    const SQL_HIDE_PROD =  "UPDATE ".self::SQL_TABLE_PRODUIT." SET etatProd=0 WHERE idProd=?";
    const SQL_DELETE_PROD = "DELETE FROM ".self::SQL_TABLE_PRODUIT." WHERE idProd=?";
    const SQL_CHECK_PROD = "SELECT * FROM ".self::SQL_TABLE_ACHAT." WHERE idProd=?";

    //Consernant les images des produits
    const SQL_INSERT_IMG = "INSERT INTO ".self::SQL_TABLE_IMG_PRODUIT."(idProd, nomImgProd, lienImgProd, extImgProd) VALUES(?,?,?,?)";
    const SQL_UPDATE_IMG = "UPDATE ".self::SQL_TABLE_IMG_PRODUIT." SET nomImgProd=?, lienImgProd=?, extImgProd=? WHERE idImgProd=?";
    const SQL_DELETE_IMG = "DELETE FROM ".self::SQL_TABLE_IMG_PRODUIT." WHERE idImgProd=?";
    const SQL_DELETE_GRUP_IMG = "DELETE FROM ".self::SQL_TABLE_IMG_PRODUIT." WHERE idProd=?";
    const SQL_SELECT_IMG = "SELECT * FROM ".self::SQL_TABLE_IMG_PRODUIT." WHERE idProd=?";
    const SQL_SELECT_LINE_IMG = "SELECT * FROM ".self::SQL_TABLE_IMG_PRODUIT." WHERE idImgProd=?";
    const SQL_SELECT_NB_IMG = "SELECT count(idImgProd) nb FROM ".self::SQL_TABLE_IMG_PRODUIT." WHERE idProd=?";

    //Gestion des méthodes
    public static function checkProd($idProd){
        global $DB;

        $rest = $DB->query(self::SQL_CHECK_PROD, [$idProd]);

        if(count($rest))
            return true;
        else
            return false;
    }

    public static function insertProd($idCptVend, $idCatVent, $nomProd, $descProd, $prixProd, $prixPromoProd){
        global $DB;

        $DB->query(self::SQL_INSERT_PROD, [$idCptVend, $idCatVent, $nomProd, $descProd, $prixProd, $prixPromoProd]);

        return $DB->lastInsertId();
    }

    public static function updateProd($idCatVent, $nomProd, $descProd, $prixProd, $prixPromoProd,$idProd){
        global $DB;

        $DB->query(self::SQL_UPDATE_PROD, [$idCatVent, $nomProd, $descProd, $prixProd, $prixPromoProd,$idProd]);

    }

    public static function listProd($idCptVend){
        global $DB;

        if($idCptVend ==1)
            return $DB->query(self::SQL_SELECT_ALL_PROD);
        else
            return $DB->query(self::SQL_SELECT_PROD_CPT, [$idCptVend]);
    }

    public static function deleteProd($idProd){
        global $DB;

        if(self::checkProd($idProd)){
            $DB->query(self::SQL_HIDE_PROD, [$idProd]);
        }else{
            $DB->query(self::SQL_DELETE_PROD, [$idProd]);
            self::deleteGrupImgProd($idProd);
        }    
    }

    //Gestion IMG PROD
    public static function insertImgProd($idProd, $nomImgProd, $lienImgProd, $extImgProd){
        global $DB;

        $DB->query(self::SQL_INSERT_IMG, [$idProd, $nomImgProd, $lienImgProd, $extImgProd]);
    }

    public static function updateImgProd($nomImgProd, $lienImgProd, $extImgProd, $idImgProd){
        global $DB;

        $DB->query(self::SQL_UPDATE_IMG, [$nomImgProd, $lienImgProd, $extImgProd, $idImgProd]);
    }

    public static function deleteImgProd($idImgProd){
        global $DB;

        $DB->query(self::SQL_DELETE_IMG, [$idImgProd]);

    }

    public static function deleteGrupImgProd($idProd){
        global $DB;

        $DB->query(self::SQL_DELETE_GRUP_IMG, [$idProd]);
    }

    public static function listImgProd($idProd){
        global $DB;

        return $DB->query(self::SQL_SELECT_IMG, [$idProd]);
    }

    public static function lineImgProd($idImgProd){
        global $DB;

        return $DB->row(self::SQL_SELECT_LINE_IMG, [$idImgProd]);
    }

    public static function nbImgProd($idProd){
        global $DB;

        $rest = $DB->row(self::SQL_SELECT_NB_IMG, [$idProd]);

        return $rest['nb'];
    }

}