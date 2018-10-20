<?php

// Autoloader -----------------------------------------------------------------
require dirname(__DIR__) . '/vendor/autoload.php';

// Constants ------------------------------------------------------------------
foreach ([
    'APP_NAME' => 'FoWDB - Force of Will Database',
    'APP_URL' => 'https://www.fowdb.altervista.org',
    'APP_HOST' => 'www.fowdb.altervista.org',
    'APP_ENV' => 'html',
    
    // Directories
    'DIR_ROOT' => dirname(dirname(__DIR__)),
    'DIR_SRC' => dirname(__DIR__),
    'DIR_VIEWS' => dirname(__DIR__).'/resources/views',
    'DIR_CACHE' => dirname(__DIR__).'/cache',
    
    // Database
    'DB_HOST' => '127.0.0.1',
    'DB_NAME' => 'my_fowdb',
    'DB_USER' => 'fowdb',
    'DB_PASSWORD' => 'UVS4JTpzFPJ8ed3Z',
    'DB_RESULTS_LIMIT' => 25,
] as $key => $value) {
    define($key, $value);
}

// Functions ---------------------------------------------------------------
require dirname(__DIR__) . '/app/functions/helpers.php';

// Legacy ------------------------------------------------------------------
$thereWereResults = false;

// Anti-CSRF token ---------------------------------------------------------
session_start();
if (!\App\Services\CsrfToken::exists()) {
    \App\Services\CsrfToken::create();
}

// Global exception handling
set_exception_handler([\App\Exceptions\Handler::class, 'handler']);
