<?php

/**
 * LOCAL ou SERVEUR
 */
$is_local = file_exists($_SERVER['DOCUMENT_ROOT'] . '/topcosmetics/local-server');


if ($is_local) {
    define('SITE_URL', "http://{$_SERVER['HTTP_HOST']}/topcosmetics/");
    define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . "/topcosmetics/");
    define('TMP_DIR', $_SERVER['DOCUMENT_ROOT'] . "/topcosmetics/mg-runtime/tmp/");
    //
    define('REQUEST_URL', "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
} else {
    define('SITE_URL', "https://{$_SERVER['HTTP_HOST']}/topcosmetics/");
    define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . "/topcosmetics/");
    define('TMP_DIR', $_SERVER['DOCUMENT_ROOT'] . "/topcosmetics/mg-runtime/tmp/");
    //
    define('REQUEST_URL', "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
}

/**
 * Redirection en HTTPS si HTTP
 */
if (!$is_local) {
    if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
        $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $redirect);
        exit();
    }
}

if (!defined('BASE_DIR')) define('BASE_DIR', __DIR__ . "/");
if (!defined('TMP_DIR')) define('TMP_DIR', BASE_DIR . "mg-runtime/tmp/");

/**
 * Error_log_config BASE_DIR . 
 */
$error_log_file = BASE_DIR ."mg-runtime/error_customed.log";

if (!is_file($error_log_file)) {
    if (!file_put_contents($error_log_file, "")) {
        //echo "Fichier non créé";
    }
}


ini_set('error_reporting', E_ALL);
error_reporting(E_ALL | E_STRICT);
ini_set('log_errors', TRUE);
ini_set('html_errors', FALSE);
ini_set('error_log', $error_log_file);
ini_set('display_errors', FALSE);
//
if ($is_local)
    ini_set('max_execution_time', 600);
    