<?php

/*
 * ---------------------------------------------------------
 * CONTROLES : Contient des fonctions de controle de données
 * ---------------------------------------------------------
 */
mb_internal_encoding("UTF-8");

/**
 * Vérifie si l'enregistrement ($colums => $values) que l'on veut insérer
 * possède déjà une occurence dans la table $table.
 * Retourne "true" si au moins une occurence existe dans la table
 * 
 * @param string $table table de l'enregistrement
 * @param array $columns Liste des colonnes insérée dans la table
 * @param array $values Liste de valeurs correspondant aux colonnes
 * @param array $idtab identifiant de la ligne ['nom_colonne' => valeur]
 * @return boolean
 */
function doubleCheck($table, $columns, $values, $idtab = NULL) {
    //Eliminons les espaces de début et de fin
    foreach ($values as &$value)
        $value = trim($value);
    /**
     * Création et exécution de la requête SQL de vérification
     */
    $sql = "SELECT COUNT(*) AS nlines FROM $table WHERE ";
    //Ajout de l'identifiant à la requête
    if ($idtab !== NULL)
        $sql.=$idtab[0] . "!=" . $idtab[1] . " AND ";
    //Ajout des colonnes à vérifier à la requête
    for ($i = 0; $i < count($columns); $i++) {
        $sql.=$columns[$i] . "=? AND ";
    }
    $sql = rtrim($sql, "AND ");
    //error_log($sql);
    //Exécution de la requête
    $result = $GLOBALS['l']->selectOne($sql, $values);
    /**
     * Renvoi de la réponse
     */
    if ($result['nlines'] != 0) //Il y a doublon
        return true;
    //Pas de doublons
    return false;
}

/**
 * Vérifie la validité d'un numéro de téléphone.
 * Le numéro valide contient uniquement des chiffres et des espaces
 * 
 * @param string $phone Le numéro de téléphone
 * @return boolean
 */
function phoneCheck($phone) {
    //Eliminons les espaces de début et de fin
    $phone = trim($phone);

    //Vérification de la validité du numéro de téléphone
    $check = preg_match('#[^0-9/ ]#', $phone);

    //Renvoi de la réponse
    if ($check == 0) //Pas de erreur	
        return true;
    else //Il y a erreur
        return false;
}

/**
 * Vérifie si une chaine de caractère est une entrier
 * 
 * @param mixed $integer Chaine à vérifier
 * @return boolean
 */
function intCheck($integer) {
    //Eliminons les espaces de début et de fin    
    if(is_empty($integer))
        return false;
    if(!is_numeric($integer))
        return false;
    
    
    //Vérifiions si la chaine est un entier
    $check = preg_match('#[^0-9]#', abs($integer));

    //Renvoi de la réponse
    if ($check == 0) //C'est un entier
        return true;
    //C'est pas un entier
    return false;
}

function check_datetime($date, $format = 'Y-m-d H:i:s') {
    return Tools_DateTime::isDateTime($date, $format);
}

function is_date_fr($date){
    return Tools_DateTime::isDateFr($date);
}

?>