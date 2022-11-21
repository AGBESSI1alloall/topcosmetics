<?php

//namespace App;

class GeneralClass
{
    /*     * *****
     * Tout le texte en Majuscule
     */
    const etat_on = 1;
    const etat_off = 0;

    //Classification des mangas
    //0:Decouverte,1:Nouveauté,2:top_shonen
    const decouverte = 0;
    const nouveaute = 1;
    const top_shonen = 2;
    //Les libellés
    const lib_decouverte = "Découverte";
    const lib_nouveaute = "Nouveauté";
    const lib_top_shonen = "top_shonen";
    //lien de slider
    const none_lien = 0;
    const abonn_lien = 2;
    const manga_lien = 1;
    //lib lien de slider
    const lib_manga = "Manga";
    const lib_abonn = "Abonnement";
    const lib_none = "Aucun";
    //Etat de Manga
    const statut_manga_encours = 0;
    const statut_manga_termine = 1;

    const lib_statut_manga_encours = "En cours";
    const lib_statut_manga_terminer = "Terminé";

    public static function listStatutManga()
    {
        return [
            self::statut_manga_encours => self::lib_statut_manga_encours,
            self::statut_manga_termine => self::lib_statut_manga_terminer
        ];
    }


    public static $MOIS = "MENSUELLE";
    public static $TRIMESTRE = "TRIMESTRIELLE";
    public static $SEMESTRE = "SEMESTRIELLE";

    public static function lien_slider()
    {
        return [
            self::none_lien => self::lib_none,
            self::manga_lien => self::lib_manga,
            self::abonn_lien => self::lib_abonn
        ];
    }
    public static function manga_classement()
    {
        return [
            self::decouverte => self::lib_decouverte,
            self::nouveaute => self::lib_nouveaute,
            self::top_shonen => self::lib_top_shonen
        ];
    }
    public static function text_uppercase($t)
    {

        $t = strtoupper($t);

        return $t;
    }

    /*     * **
     * Tout le texte en Minuscule
     */

    public static function text_tolower($t)
    {

        $t = strtolower($t);

        return $t;
    }

    /*     * *
     * La premiere lettre en Majuscule
     */

    public static function text_ucfirst($t)
    {

        $t = ucfirst(strtolower($t));

        return $t;
    }

    /*     * *
     * Gerer les mots de passe Aléatoire
     */

    public static function MdpAleatoire($taille)
    {
        // Liste des caractères possibles
        $cars = "azertyiopqsdfghjklmwxcvbn0123456789";
        $mdp = '';
        $long = strlen($cars);

        srand((float) microtime() * 1000000);
        //Initialise le générateur de nombres aléatoires

        for ($i = 0; $i < $taille; $i++)
            $mdp = $mdp . substr($cars, rand(0, $long - 1), 1);

        return $mdp;
    }

    /*     * *
     * Prix des produits en format français
     * 
     */

    public static function prix_cfa($nomber, $devise = true, $lang = 'fr')
    {
        if ($lang === 'fr')
            $nombre = number_format($nomber, 0, ',', ' ');
        else
            $nombre = number_format($nomber);

        if ($devise)
            return $nombre . " FCFA";
        else
            return $nombre;
    }

    public static function dateFormatFr($date, $format = 'd/m/Y')
    {

        $res = date($format, strtotime($date));

        return $res;
    }

    public static function dateHeureFormatFr($date, $format = 'd/m/Y H:i:s')
    {

        $res = date($format, strtotime($date));

        return $res;
    }

    public static function nbJoursExpiration($date1, $date2 = null)
    {

        $date2 = empty($date2) ? date('Y-m-d') : $date2;

        $date1 = new DateTime("$date1");

        $date2 = new DateTime("$date2");

        $nb_jours = $date2->diff($date1)->format("%a");

        if($date1 > $date2)
            return -$nb_jours;
        else
            return $nb_jours;
    }

    public static function nbJours2Dates($date1, $date2 = null)
    {

        $date2 = empty($date2) ? date('Y-m-d') : $date2;

        $date1 = new DateTime("$date1");

        $date2 = new DateTime("$date2");

        $nb_jours = $date2->diff($date1)->format("%a");

        return $nb_jours;
    }

    /*
     * LA GESTION DES CREDITS
     */

    public static function nextDateCredit($date, $periodicite)
    {
        if ($periodicite == "m") {
            $ecart = 1;
        } else if ($periodicite == "t") {
            $ecart = 3;
        } else {
            $ecart = 6;
        }

        $dur = "+$ecart month";

        $ladate = date('Y-m-d', strtotime($dur, strtotime($date)));

        return $ladate;
    }

    public static function creditCalcul($somme_emp, $taux_general, $d, $date, $type_rem = "m", $type_pret, $type_taux = "g")
    {

        $n = 0;

        $all_out = $tab_amort = [];

        $dp = $dd = "";

        $type_rem = self::text_tolower($type_rem);

        $type_taux = self::text_tolower($type_taux);


        if (in_array($type_rem, ["m", "t", "s"])) {
            if ($type_rem == "m") {
                $n = 12;
                $ecart = 1;
                $lib = self::$MOIS;
            } else if ($type_rem == "t") {
                $ecart = 3;
                $n = 4;
                $lib = self::$TRIMESTRE;
            } else {
                $n = 2;
                $ecart = 6;
                $lib = self::$SEMESTRE;
            }
        } else {

            $n = 12;
        }

        //taux en non pourcentage
        $taux_general = str_replace(" ", "", $taux_general);
        $taux_general = str_replace(",", ".", $taux_general);
        $taux_annuel = $taux_general / 100;

        //Calcul du taux périodique
        $taux_periodique = 0;
        if ($type_taux == "g")
            $taux_periodique = $taux_annuel / $n;
        else
            $taux_periodique = pow((1 + $taux_annuel), (1 / $n)) - 1;

        //Montant periodique
        $montant_periodique = ($somme_emp * $taux_periodique * pow((1 + $taux_periodique), $d)) / (pow((1 + $taux_periodique), $d) - 1);

        $list_interet = "";
        $nb = 0;
        for ($i = 0; $i < $d; $i++) {

            $nb++;
            $rp = round($montant_periodique, 0);
            $assur = 0;
            if ($i == 0)
                $som_rest = $somme_emp;

            $int = $som_rest * $taux_periodique;
            $cap = $montant_periodique - $int;
            $solde = $som_rest - $cap;

            $ladate = $date;

            $prec = $nb * $ecart;

            $dur = "+$prec month";

            $ladate = date('Y-m-d', strtotime($dur, strtotime($ladate)));

            if ($i == 0)
                $dp = $ladate;
            else
                $dd = $ladate;

            $int = round($int, 0);
            $list_interet .= "$int-";
            $int = self::prix_cfa($int, false);

            $tab_amort[] = [
                'num' => $nb,
                'date' => date('d/m/Y', strtotime($ladate)),
                'remb' => self::prix_cfa(round($rp, 0), false),
                'int' => $int,
                'cap' => self::prix_cfa(round($cap, 0), false),
                'assur' => $assur,
                'solde' => self::prix_cfa(round($solde, 0), false),
            ];
        }

        $d_an = $d / 12;
        if ($d_an < 1)
            $d_an = 0;
        else
            $d_an = round($d_an, 1);


        $mtr = $montant_periodique * $d;
        $intT = $mtr - $somme_emp;

        $type_pret = explode("-", $type_pret);

        $id_type_pret = $type_pret[0];
        $lib_type_pret = $type_pret[1];
        $frais_dossier = $type_pret[2];

        $list_interet = substr($list_interet, 0, -1);

        $all_out_first = [
            'n' => 1,
            'an' => $d_an,
            'taux' => $taux_general,
            'duree' => $d,
            'mt' => self::prix_cfa($somme_emp, false),
            'mp' => self::prix_cfa(round($montant_periodique, 0), false),
            'period' => $lib,
            'dpr' => self::dateFormatFr($dp),
            'ddr' => self::dateFormatFr($dd),
            'ddb' => self::dateFormatFr($date),
            'amort' => $tab_amort,
            'mtR' => self::prix_cfa(round($mtr, 0), false),
            'intT' => self::prix_cfa(round($intT, 0), false),
            'type_pret' => $lib_type_pret,
            'id_type_pret' => $id_type_pret,
            'frais_dossier' => self::prix_cfa($frais_dossier, false),
            'listInt' => $list_interet
        ];

        $all_out = [
            'n' => 1,
            'an' => $d_an,
            'taux' => $taux_general,
            'duree' => $d,
            'mt' => $somme_emp,
            'mp' => $montant_periodique,
            'period' => $lib,
            'dpr' => $dp,
            'ddr' => $dd,
            'ddb' => $date,
            'amort' => $tab_amort,
            'mtR' => $mtr,
            'intT' => $intT,
            'type_pret' => $lib_type_pret,
            'id_type_pret' => $id_type_pret,
            'frais_dossier' => $frais_dossier,
            'listInt' => $list_interet
        ];
        return [$all_out_first, $all_out];
    }

    /*
     * Verification de doublons
     */
    public static function checkDoublons($value, $table, $champ)
    {
        global $DB;

        $sql = "SELECT count(*) AS nb_donn FROM $table WHERE $champ=?";
        $result = $DB->row($sql, [$value]);

        //return $sql.$value;
        if ($result['nb_donn'] != 0)
            return true;
        else
            return false;
    }

    public static function sendNotificationFireBase($titre, $body, $token)
    {
        // FCM API Url
        $url = 'https://fcm.googleapis.com/fcm/send';

        // Put your Server Key here
        $apiKey = "AAAAu3KvEEk:APA91bE9zsPC-Hfp7gPsDl-HtpIgaFibpMKHWOND-SKu6RVigNvcGpoUn0oaXYS0GB4AsK2Hs8BENUZPx-E9bhZizWSSd5Daqw3lYMshxUGIq_RH9-e8-QPvLb9HK7YqKRpToWlIrDF5";

        // Compile headers in one variable
        $headers = array(
            'Authorization:key=' . $apiKey,
            'Content-Type:application/json'
        );

        // Add notification content to a variable for easy reference
        $notifData = [
            'title' => $titre,
            'body' => $body,
            //  "image": "url-to-image",//Optional
            'click_action' => "activities.NotifHandlerActivity" //Action/Activity - Optional
        ];

        $dataPayload = [
            'to' => 'Mangadojo.net',
            'points' => 80,
            'other_data' => 'This is extra payload'
        ];

        // Create the api body
        $apiBody = [
            'notification' => $notifData,
            'data' => $dataPayload, //Optional
            'time_to_live' => 600, // optional - In Seconds
            //'to' => '/topics/mytargettopic'
            //'registration_ids' = ID ARRAY
            'to' => $token
        ];

        // Initialize curl with the prepared headers and body
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));

        // Execute call and save result
        $result = curl_exec($ch);
        $result = json_decode($result);

        if (!isset($result->{'multicast_id'}))
            $r = 0;
        else
            $r = 1;

        //print($result);

        // Close curl after call
        curl_close($ch);

        return $r;
    }
}
