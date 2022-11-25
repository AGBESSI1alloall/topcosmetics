<?php

class Menu extends GeneralClass
{

    const SQL_TABLE_MENU = "menu";
    const SQL_TABLE_SOUSMENU = "sousmenu";
    const SQL_TABLE_MENUUSER = "menu_user";

    const SELECT_MENU_GENERAL = "SELECT sm.*,m.menu FROM ".self::SQL_TABLE_SOUSMENU." sm JOIN ".self::SQL_TABLE_MENU." m USING(idMenu) ORDER BY idMenu, idSousMenu";
    const SELECT_MENU_USER = "SELECT sm.*, m.menu FROM ".self::SQL_TABLE_MENUUSER." mu JOIN ".self::SQL_TABLE_MENU." 
    m USING (idMenu) JOIN ".self::SQL_TABLE_SOUSMENU." sm USING(idSousMenu) WHERE idUser=? ORDER BY mu.idMenu, mu.idSousMenu";
    const SQL_DELETE_MENU_USER = "DELETE FROM ".self::SQL_TABLE_MENUUSER." WHERE idUser = ?";
    const SQL_INSERT_MENU_USER = "INSERT INTO ".self::SQL_TABLE_MENUUSER."(idUser, idMenu, idSousMenu) VALUES (?, ?, ?)";

    public static function listMenu()
    {
        global $DB;

        return $DB->query(self::SELECT_MENU_GENERAL);
    }

    public static function listUserMenu($iduser)
    {
        global $DB;

        return $DB->query(self::SELECT_MENU_USER, [$iduser]);
    }

    public static function deleteMenuUser($iduser){
        global $DB;

        $DB->query(self::SQL_DELETE_MENU_USER, [$iduser]);
    }

    public static function insertMenuUser($iduser, $val, $l){
        global $DB;

        $DB->query(self::SQL_INSERT_MENU_USER, [$iduser, $val, $l]);
    }
}
