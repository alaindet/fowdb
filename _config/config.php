<?php

// Session --------------------------------------------------------------------
session_start();

// Constants ------------------------------------------------------------------
foreach ([
    "APP_NAME" => "FoWDB - Force of Will Database",
    "APP_DOMAIN" => "http://fowdb.altervista.org/",
    "APP_URL" => "http://fowdb.altervista.org", // Alias
    "APP_ROOT" => dirname(__DIR__),
    "APP_DB_HOST" => "127.0.0.1",
    "APP_DB_NAME" => "my_fowdb",
    "APP_DB_USER" => "fowdb",
    "APP_DB_PASSWORD" => "UVS4JTpzFPJ8ed3Z",
    "APP_RESULTS_LIMIT" => 25
] as $key => $value) {
    define($key, $value);
}

// Autoloader
require dirname(__DIR__) . '/src/vendor/autoload.php';

// Functions
require __DIR__ . '/functions/helpers.php';
require __DIR__ . '/functions/auth.php';

// Legacy ---------------------------------------------------------------------
$thereWereResults = false;

// Anti-CSRF token ------------------------------------------------------------
if (! isset($_SESSION['token'])) {
    $_SESSION['token'] = sha1(uniqid(mt_rand(), true));
}
