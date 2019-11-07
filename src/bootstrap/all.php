<?php

$src = dirname(__DIR__);

require $src . "/vendor/autoload.php";

$config = \App\Services\Config\Config::getInstance($src);

require $src . "/app/functions/helpers.php";

// Global exception handling
set_exception_handler([\App\Exceptions\Handler::class, "handler"]);

// Global error handling
// https://stackoverflow.com/a/51091503/5653974
set_error_handler([\App\Exceptions\Handler::class, "errorHandler"], E_ALL);
