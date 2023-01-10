<?php 
class CommandeAchat extends GeneralClass {
    
    const SQL_TABLE_CMD = "commande";
    const SQL_TABLE_ACHAT = "achat";
    const SQL_TABLE_PRODUIT = "produit";
    const SQL_TABLE_TYP_PAIEM = "type_paiement";
    const SQL_TABLE_CLIENT = "client";
    const SQL_TABLE_CV = "compte_vendeur";

    /**GESTION COMMANDES PAR VENDEUR */
    const SQL_LIST_CMD_CPT = "SELECT a.idCom FROM ".self::SQL_TABLE_ACHAT." a JOIN 
    ".self::SQL_TABLE_PRODUIT." p USING(idProd) JOIN ".self::SQL_TABLE_CMD.
    " c USING(idCom) WHERE p.idCptVend=? AND c.etatLivCom IN (1,2) AND etatCom=1";

    const SQL_LIST_CMD_CPT_H = "SELECT a.idCom FROM ".self::SQL_TABLE_ACHAT." a JOIN 
    ".self::SQL_TABLE_PRODUIT." p USING(idProd) JOIN ".self::SQL_TABLE_CMD.
    " c USING(idCom) WHERE p.idCptVend=? AND c.etatLivCom IN (3,0) AND etatCom=1 AND DateCom >= ? AND DateCom <= ?";

    const SQL_SOM_CMD_CPT = "SELECT SUM(SomTotAchat) somme FROM ".self::SQL_TABLE_ACHAT." a JOIN 
    ".self::SQL_TABLE_PRODUIT." p USING(idProd)WHERE idCom=? AND idCptVend=?";

    const SQL_DETAIL_CMD_CPT ="SELECT p.nomProd, cv.nomCptVend, a.* FROM ".self::SQL_TABLE_ACHAT." a JOIN 
    ".self::SQL_TABLE_PRODUIT." p USING(idProd) JOIN ".self::SQL_TABLE_CV." cv USING(idCptVend) WHERE idCom=? AND p.idCptVend=?";

    const SQL_COMPTE_VUE = "SELECT * FROM ".self::SQL_TABLE_CMD." WHERE vueCmd = 0";

    const SQL_CONFIRM_PAIEM = "UPDATE ".self::SQL_TABLE_CMD." SET payer=1, etatLivCom=2, idUser=? WHERE idCom=?";

    const SQL_ANNULER_CMD = "UPDATE ".self::SQL_TABLE_CMD." SET payer=0, etatLivCom=0, motif_annulation=? idUser=? WHERE idCom=?";
    /**GESTION COMMANDES PAR VENDEUR */

    /**GESTION COMMANDES POUR SUPER ADMIN */
    const SQL_LIST_CMD = "SELECT * FROM ".self::SQL_TABLE_CMD." c JOIN 
    ".self::SQL_TABLE_TYP_PAIEM." tp USING(idTypePaiem) JOIN ".self::SQL_TABLE_CLIENT.
    " clt USING(idClt) WHERE c.etatLivCom IN (1,2) AND etatCom=1";

    const SQL_LIST_CMD_HISTO = "SELECT * FROM ".self::SQL_TABLE_CMD." c JOIN 
    ".self::SQL_TABLE_TYP_PAIEM." tp USING(idTypePaiem) JOIN ".self::SQL_TABLE_CLIENT.
    " clt USING(idClt) WHERE c.etatLivCom IN (3,0) AND etatCom=1 AND DateCom >= ? AND DateCom <= ?";

    const SQL_LIST_VENDEUR = "SELECT cv.nomCptVend FROM ".self::SQL_TABLE_ACHAT.
    " a JOIN ".self::SQL_TABLE_PRODUIT." p USING(idProd) JOIN ".self::SQL_TABLE_CV." cv USING(idCptVend) WHERE idCom=?";

    const SQL_DETAIL_CMD ="SELECT p.nomProd, cv.nomCptVend, a.* FROM ".self::SQL_TABLE_ACHAT." a JOIN 
    ".self::SQL_TABLE_PRODUIT." p USING(idProd) JOIN ".self::SQL_TABLE_CV." cv USING(idCptVend) WHERE idCom=?";

    static function annulationCommande($motif, $idUser, $idCom){
        global $DB;

        $DB->query(self::SQL_ANNULER_CMD, [$motif, $idUser, $idCom]);
    }
    
    static function confirmPaiement($idCom, $idUser){
        global $DB;

        $DB->query(self::SQL_CONFIRM_PAIEM, [$idUser, $idCom]);
    }

    static function nbCommandeNonLue(){
        global $DB;

        $rest = $DB->query(self::SQL_COMPTE_VUE);

        return count($rest);
    }

    static function updateVueCmd(){
        global $DB;

        $sql = "UPDATE ".self::SQL_TABLE_CMD." SET vueCmd=1 WHERE vueCmd=0";

        $DB->query($sql);

    }
    static function listGeneralCmdHisto($dd, $df){
        global $DB;

        return $DB->query(self::SQL_LIST_CMD_HISTO, [$dd, $df]);
    }

    static function listGeneralCmd(){
        global $DB;

        return $DB->query(self::SQL_LIST_CMD);
    }

    static function listVendeurs($idCom){
        global $DB;

        $rest = $DB->query(self::SQL_LIST_VENDEUR, [$idCom]);

        return $rest = implode(",", array_column($rest, 'nomCptVend'));
    }

    public static function cmdForCpt($idCptVend){
        global $DB;

        $rest = $DB->query(self::SQL_LIST_CMD_CPT, [$idCptVend]);

        $rest = count($rest) ? implode(",", array_unique(array_column($rest, 'idCom'))) : 0;

        $sql = "SELECT * FROM ".self::SQL_TABLE_CMD." c JOIN 
        ".self::SQL_TABLE_TYP_PAIEM." tp USING(idTypePaiem) JOIN ".self::SQL_TABLE_CLIENT." clt USING(idClt) WHERE idCom IN ($rest)";

        return $DB->query($sql);
    }

    public static function cmdHistoForCpt($idCptVend, $dd, $df){
        global $DB;

        $rest = $DB->query(self::SQL_LIST_CMD_CPT_H, [$idCptVend, $dd, $df]);

        $rest = count($rest) ? implode(",", array_unique(array_column($rest, 'idCom'))) : 0;

        $sql = "SELECT * FROM ".self::SQL_TABLE_CMD." c JOIN 
        ".self::SQL_TABLE_TYP_PAIEM." tp USING(idTypePaiem) JOIN ".self::SQL_TABLE_CLIENT." clt USING(idClt) WHERE idCom IN ($rest)";

        return $DB->query($sql);
    }

    public static function somComForCpt($idCom, $idCptVend){
        global $DB;

        $result = $DB->row(self::SQL_SOM_CMD_CPT, [$idCom, $idCptVend]);

        return $result['somme'];
    }

    public static function detailComForCpt($idCom, $idCptVend){
        global $DB;

        if($idCptVend != 1)
            return $DB->query(self::SQL_DETAIL_CMD_CPT, [$idCom, $idCptVend]);
        else
            return $DB->query(self::SQL_DETAIL_CMD, [$idCom]);
    }
}