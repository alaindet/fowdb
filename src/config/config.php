<?php

// Session --------------------------------------------------------------------
session_start();

// Constants ------------------------------------------------------------------
foreach ([
    "APP_NAME" => "FoWDB - Force of Will Database",
    "APP_URL" => "https://fowdb.altervista.org",
    
    // Directories
    "DIR_ROOT" => dirname(dirname(__DIR__)),
    "DIR_APP" => dirname(__DIR__),
    
    // Database
    "DB_HOST" => "127.0.0.1",
    "DB_NAME" => "my_fowdb",
    "DB_USER" => "fowdb",
    "DB_PASSWORD" => "UVS4JTpzFPJ8ed3Z",
    "DB_RESULTS_LIMIT" => 25,
    
    // Legacy
    // "APP_DB_HOST" => "127.0.0.1",
    // "APP_DB_NAME" => "my_fowdb",
    // "APP_DB_USER" => "fowdb",
    // "APP_DB_PASSWORD" => "UVS4JTpzFPJ8ed3Z",
    // "APP_RESULTS_LIMIT" => 25,

    // "APP_DOMAIN" => "https://fowdb.altervista.org/",
    "APP_ROOT" => dirname(dirname(__DIR__)),
] as $key => $value) {
    define($key, $value);
}

// Autoloader
require dirname(__DIR__) . '/vendor/autoload.php';

// Functions
require dirname(__DIR__) . '/app/functions/helpers.php';
require dirname(__DIR__) . '/app/functions/auth.php';

// Legacy ---------------------------------------------------------------------
$thereWereResults = false;

// Anti-CSRF token ------------------------------------------------------------
if (! isset($_SESSION['token'])) {
    $_SESSION['token'] = sha1(uniqid(mt_rand(), true));
}
