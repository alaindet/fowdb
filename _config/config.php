<?php

// SESSION --------------------------------------------------------------------
session_start();

// CONSTANTS ------------------------------------------------------------------
foreach ([
    "APP_NAME" => "FoWDB - Force of Will Database",
    "APP_DOMAIN" => "http://fowdb.altervista.org/",
    "APP_ROOT" => dirname(__DIR__),
    "APP_DB_HOST" => "127.0.0.1",
    "APP_DB_NAME" => "my_fowdb",
    "APP_DB_USER" => "fowdb",
    "APP_DB_PASSWORD" => "",
    // Default JSON_* flags for json_encode() function
    // *_NUMERIC_CHECK preserves integers as int, not strings
    // *_UNESCAPED_SLASHES prevents escaping slashes into strings
    // *_PRETTY_PRINT preserves white space for readability of output file
    "APP_JSON_ENCODE" => JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES,
    "APP_RESULTS_LIMIT" => 25
] as $key => $value) {
    define($key, $value);
}

// Autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

// LEGACY ---------------------------------------------------------------------
$thereWereResults = false; // Search flag
include APP_ROOT.'/_config/helpers.php'; // Helper functions

// ANTI-CSRF TOKEN ------------------------------------------------------------
if (! isset($_SESSION['token'])) {
    $_SESSION['token'] = sha1(uniqid(mt_rand(), true));
}

// AUTOLOADER -----------------------------------------------------------------
// function __autoload($classname)
// {
//     include APP_ROOT.'/_includes/classes/class.'.strtolower($classname).'.php';
// }

// EXCEPTIONS -----------------------------------------------------------------
// set_exception_handler(function ($e)
// {
//     // // Get error page
//     // $errorPage = file_get_contents(APP_ROOT . "/_template/errorPage.html");
//     // // Display error page
//     // echo str_replace("<!--#errorMessage#-->", $e->getMessage(), $errorPage);
    
//     // DEV
//     echo "ERROR: (DEV)\n",$e->getMessage();
// });
