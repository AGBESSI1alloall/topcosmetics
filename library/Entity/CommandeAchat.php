<?php 
class CommandeAchat extends GeneralClass {
    
    const SQL_TABLE_CMD = "commande";
    const SQL_TABLE_ACHAT = "achat";
    const SQL_TABLE_PRODUIT = "produit";

    const SQL_LIST_CMD_CPT = "SELECT a.idCom FROM ".self::SQL_TABLE_ACHAT." a JOIN 
    ".self::SQL_TABLE_PRODUIT." p USING(idProd) JOIN ".self::SQL_TABLE_CMD.
    " c USING(idCom) WHERE p.idCptVend=? AND c.etatLivCom IN (1,2) AND etatCom=1";

    const SQL_LIST_CMD_CPT_H = "SELECT a.idCom FROM ".self::SQL_TABLE_ACHAT." a JOIN 
    ".self::SQL_TABLE_PRODUIT." p USING(idProd) JOIN ".self::SQL_TABLE_CMD.
    " c USING(idCom) WHERE p.idCptVend=? AND c.etatLivCom IN (3) AND etatCom=1";

    const SQL_SOM_CMD_CPT = "SELECT SUM(SomTotAchat) somme FROM ".self::SQL_TABLE_ACHAT." WHERE idCom=? AND idCptVend=?";

    const SQL_DETAIL_CMD_CPT ="SELECT p.nomProd, a.* FROM ".self::SQL_TABLE_ACHAT." a JOIN 
    ".self::SQL_TABLE_PRODUIT." p USING(idProd) WHERE idCom=? AND idCptVend=?";



    public static function cmdForCpt($idCptVend){
        global $DB;

        $rest = $DB->query(self::SQL_LIST_CMD_CPT, [$idCptVend]);

        $rest = implode(",", array_unique(array_column($rest, 'idCom')));

        $sql = "SELECT * FROM ".self::SQL_TABLE_CMD." WHERE idCom IN ($rest)";

        return $DB->query($sql);
    }

    public static function cmdHistoForCpt($idCptVend){
        global $DB;

        $rest = $DB->query(self::SQL_LIST_CMD_CPT_H, [$idCptVend]);

        $rest = implode(",", array_unique(array_column($rest, 'idCom')));

        $sql = "SELECT * FROM ".self::SQL_TABLE_CMD." WHERE idCom IN ($rest)";

        return $DB->query($sql);
    }

    public static function somComForCpt($idCom, $idCptVend){
        global $DB;

        return $DB->row(self::SQL_SOM_CMD_CPT, [$idCom, $idCptVend]);

    }

    public static function detailComForCpt($idCom, $idCptVend){
        global $DB;

        return $DB->query(self::SQL_DETAIL_CMD_CPT, [$idCom, $idCptVend]);
    }
}