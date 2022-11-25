<?php

class User extends GeneralClass {

    const SQL_TABLE_USER = "user";
    const SQL_TABLE_CV = "compte_vendeur";

    const SQL_FOR_ONE_USER = "SELECT * FROM ".self::SQL_TABLE_USER." us JOIN ".self::SQL_TABLE_CV." cv USING(idCptVend) 
    WHERE idUser=?";

    const SQL_FOR_ALL_USER_CPT = "SELECT * FROM ".self::SQL_TABLE_USER." us JOIN ".self::SQL_TABLE_CV." cv USING(idCptVend) 
    WHERE idCptVend=? AND etatUser=1 ORDER BY idUser";
    const SQL_FOR_ALL_USER = "SELECT * FROM ".self::SQL_TABLE_USER." us JOIN ".self::SQL_TABLE_CV." cv USING(idCptVend) 
    WHERE etatUser=1 ORDER BY idUser,idCptVend";
    const SQL_HIDE_USER = "UPDATE ".self::SQL_TABLE_USER." SET etatUser=0 WHERE idUser=?";
    const SQL_VERIFY_OLD_PASSWORD = "SELECT * FROM ".self::SQL_TABLE_USER." WHERE pwdUser=? AND idUser=?";
    const SQL_CHANGE_PASSWORD = "UPDATE ".self::SQL_TABLE_USER." SET pwdUser=? WHERE idUser=?";
    const SQL_UPDATE_USER = "UPDATE ".self::SQL_TABLE_USER." SET nomUser=?, prenomUser=?, telUser=?, emailUser=?, dateEditUser=NOW() WHERE idUser=?";
    const SQL_INSERT_USER = "INSERT INTO ".self::SQL_TABLE_USER."(idCptVend, nomUser, prenomUser, telUser, emailUser, pwdUser, typeUser) VALUES(?,?,?,?,?,?,?)";

    public static function lineForOneUser($iduser){
        global $DB;

        return $DB->row(self::SQL_FOR_ONE_USER, [$iduser]);

    }

    public static function listForAllUsers($idcptvend){
        global $DB;

        if($idcptvend != 1)
            return $DB->query(self::SQL_FOR_ALL_USER_CPT, [$idcptvend]);
        else 
            return $DB->query(self::SQL_FOR_ALL_USER);
    }

    public static function verifyOldPassword($password, $iduser){
        global $DB;

        $password = hash('sha256', $password);
        $result = $DB->query(self::SQL_VERIFY_OLD_PASSWORD, [$password, $iduser]);

        if(count($result))
            return true;
        else 
            return false;
    }

    public static function changePasswordUser($password, $iduser){
        global $DB;

        $password = hash('sha256', $password);

        $DB->query(self::SQL_CHANGE_PASSWORD, [$password, $iduser]);

    }

    public static function editUser($nom, $prenom, $tel, $email, $iduser){
        global $DB;

        $DB->query(self::SQL_UPDATE_USER, [$nom, $prenom, $tel, $email, $iduser]);
    }

    public static function deleteUser($iduser){
        global $DB;

        $DB->query(self::SQL_HIDE_USER, [$iduser]);
    }

    public static function insertUser($idCptVend, $nomUser, $prenomUser, $telUser, $emailUser, $pwdUser, $typeUser){
        global $DB;

        $pwdUser = hash('sha256', $pwdUser);

        $DB->query(self::SQL_INSERT_USER, [$idCptVend, $nomUser, $prenomUser, $telUser, $emailUser, $pwdUser, $typeUser]);

        return $id_user = $DB->lastInsertId();
    }

    //Affectation des menus aux utilisateur
    public static function detectMenu($type, $iduser, $val = 0, $general = "t") {

        global $DB;

        $id_menu = 0;

        if ($type == "g_vente") {
            $id_menu = 1;
        } elseif ($type == "g_message") {
            $id_menu = 2;
        }elseif ($type == "g_user") {
            $id_menu = 3;
        }elseif ($type == "g_admin") {
            $id_menu = 4;
        }

        $sql = "SELECT * FROM menu_user WHERE idMenu = $id_menu AND idUser=$iduser";
        $result = $DB->query($sql);

        if ($general == "t") {
            if (count($result) > 0)
                return true;
            else
                return false;
        } else {
            if (count($result) > 0) {
                $i = 0;
                foreach ($result as $line) {
                    if ($line['idSousMenu'] == $val)
                        $i++;
                }
                if ($i > 0)
                    return true;
                else
                    return false;
            } else
                return false;
        }
    }
}