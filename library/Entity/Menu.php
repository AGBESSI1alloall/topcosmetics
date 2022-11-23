<?php 

class Menu extends GeneralClass {

    const SQL_TABLE_MENU = "menu";
    const SQL_TABLE_SOUSMENU = "sousmenu";

    const SELECT_MENU_GENERAL = "SELECT sm.*,m.menu FROM sousmenu sm JOIN menu m USING(idMenu) ORDER BY idMenu, idSousMenu";
    const SELECT_MENU_USER = "SELECT sm.*, m.menu FROM menu_user mu JOIN menu m USING (idMenu) JOIN sousmenu sm USING(idSousMenu) WHERE idUser=? ORDER BY mu.idMenu, mu.idSousMenu";

    public static function listMenu($iduser=NULL, $typeuser = "developper"){
        global $DB;

        if(isset($iduser) && empty($iduser) && $typeuser =="developper")
            return $DB->query(self::SELECT_MENU_GENERAL);
        else
            return $DB->query(self::SELECT_MENU_USER, [$iduser]);


    }
}