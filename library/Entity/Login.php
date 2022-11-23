<?php 

class Login extends GeneralClass {

    const SQL_TABLE_USERS = "user";
    const SQL_TABLE_CV = "compte_vendeur";

    const SQL_FOR_CONNEXION = "SELECT * FROM ".self::SQL_TABLE_USERS." JOIN ".self::SQL_TABLE_CV." USING(idCptVend) 
    WHERE nomCptVend=? AND emailUser=? AND pwdUser=? AND etatUser=1 AND etatCptVend=1";

    public static function userConnexion($compte, $email, $password){
        global $DB;

        return $result = $DB->query(self::SQL_FOR_CONNEXION, [$compte, $email, $password]);

    }
}