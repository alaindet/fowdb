<?php

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/app/functions/helpers.php';

// Legacy
$thereWereResults = false;

// Anti-CSRF token
session_start();
if (!\App\Services\CsrfToken::exists()) {
    \App\Services\CsrfToken::create();
}

// Global exception handling
set_exception_handler([\App\Exceptions\Handler::class, 'handler']);
